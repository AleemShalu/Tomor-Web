<?php

namespace App\Http\Controllers\Api;

use App\Models\Offer;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductCollection;
use App\Traits\Api\UserIsAuthorizedTrait;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\ProductCategoryCollection;

class ProductController extends Controller
{
    use UserIsAuthorizedTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            // 'store_branch_id' => 'nullable|numeric|exists:App\Models\StoreBranch,id',
            'product_brand_id' => 'nullable|numeric|exists:App\Models\ProductBrand,id',
            'product_category_id' => 'nullable|numeric|exists:App\Models\ProductCategory,id',
            'locale' => 'nullable|string|exists:App\Models\Language,code',
            'currency_code' => 'required|string|in:SAR',
            // 'currency_code'=> 'required|string|exists:App\Models\Currency,code',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $locale = request('locale', null);
        $search = request('search', null);
        $brandId = request('product_brand_id', null);
        $categoryId = request('product_category_id', null);

        $localeCondition = function ($q) use ($locale, $search) {
            if (isset($locale)) {
                $q->where('locale', $locale);
            }
            if (isset($search)) {
                $q->where('name', 'like', '%' . $search . '%');
            }
        };

        $brandCondition = function ($q) use ($brandId) {
            if (isset($brandId)) {
                $q->where('product_brand_id', $brandId);
            }
        };

        $categoryCondition = function ($q) use ($categoryId) {
            if (isset($categoryId)) {
                $q->where('product_category_id', $categoryId);
            }
        };

        $listOfAvailableProducts = Product::with([
            'product_offer',
            'product_brand' => $brandCondition,
            'product_category' => $categoryCondition,
            'translations' => $localeCondition,
            'images',
            'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
        ])->addSelect(['has_active_offer' => Offer::selectRaw('
            CASE WHEN status = 1 AND start_date <= ? AND end_date >= ? THEN true ELSE false END')
            ->addBinding([now(), now()], 'select')
            ->join('product_offers', 'product_offers.offer_id', '=', 'offers.id')
            ->whereColumn('product_offers.product_id', 'products.id')
            ->orderBy('end_date', 'desc')  // to get the latest offer if there are multiple
            ->limit(1)  // important if there are multiple offers per product
        ])->where('store_id', request('store_id'))
            ->orderByDesc('has_active_offer')  // put products with active offers at the top
            ->orderBy('id', 'asc')
            ->filterByRole() // scope defined in the product model
        ->paginate(request('limit', 10));

        return ProductResource::collection($listOfAvailableProducts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        $ProductValidationRules = new ProductRequest();
        $validator = Validator::make($request->all(), $ProductValidationRules->rules());

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // create new instance of product
            $product = new Product();
            // $product->product_type_id = request('product_type_id');
            // $product->product_brand_id = request('product_brand_id');
            $product->product_code = request('product_code') ? Str::upper(request('product_code')) : null;
            $product->model_number = request('model_number') ? Str::upper(request('model_number', null)) : null;
            $product->barcode = request('barcode', null);
            $product->quantity = request('quantity', 0);
            $product->unit_price = request('unit_price', 0);
            $product->calories = request('calories', null);
            // $product->vat_code_id = request('vat_code_id', null);
            $product->status = request('status', 0); // available for sale or not
            $product->product_category_id = request('product_category_id', null);
            $product->store_id = request('store_id', null);
            $product->save();

            // add translations to the product
            if (request('translations')) {
                foreach (request('translations') as $locale) {
                    $productTranslation = new ProductTranslation();
                    $productTranslation->product_id = $product->id;
                    $productTranslation->locale = $locale['locale'] ?? null;
                    $productTranslation->name = $locale['name'] ?? null;
                    $productTranslation->excerpt = $locale['excerpt'] ?? null;
                    $productTranslation->description = $locale['description'] ?? null;
                    $productTranslation->save();
                }
            }

            // add product images
            if (request('product_images') && count(request('product_images')) > 0) {
                $image_labels = request('product_image_labels');
                $products_folder = 'stores/' . $product->store_id . '/products/' . $product->id;
                if (!File::exists(storage_path($products_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($products_folder);
                }
                foreach (request('product_images') as $i => $image) {
                    $thumb_img = $image;
                    $img_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                        $products_folder,
                        $thumb_img,
                        uniqid('product-', true) . '.' . $thumb_img->getClientOriginalExtension(),
                    );
                    // create new product image in the DB
                    $product_image = new ProductImage();
                    $product_image->product_id = $product->id;
                    $product_image->label = $image_labels[$i] ?? null;
                    $product_image->url = $img_path;
                    $product_image->save();
                }
            }

            // $product->availability = ($product->quantity <= 0) ? 'out of stock' : 'in stock';
            // $product->save();

            $product = Product::with([
                'product_offer',
                'product_brand',
                'product_category',
                'translations',
                'images',
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->findOrFail($product->id);

            return response()->json([
                "product" => ProductResource::make($product),
                "message" => __('locale.api.alert.model_created_successfully', ['model' => 'Product']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:ProductController:store: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'product_id' => 'required|numeric|exists:App\Models\Product,id',
            'locale' => 'nullable|string|exists:App\Models\Language,code',
            'currency_code' => 'required|string|in:SAR',
            // 'currency_code'=> 'required|string|exists:App\Models\Currency,code',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $product = Product::with([
                'product_offer',
                'product_brand',
                'product_category',
                'translations',
                'images',
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->filterByRole()
            ->findOrFail(request('product_id'));

            return new ProductResource($product);

        } catch (QueryException $e) {
            Log::error('API:ProductController:show: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        // 1. Find product
        $product = Product::findOrFail($id);

        $productValidationRules = new ProductRequest();
        $validator = Validator::make($request->all(), $productValidationRules->rules($id));

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // update product details
            // $product->product_type_id = request('product_type_id');
            // $product->product_brand_id = request('product_brand_id');
            $product->product_code = request('product_code') ? Str::upper(request('product_code')) : null;
            $product->model_number = request('model_number') ? Str::upper(request('model_number', null)) : null;
            $product->barcode = request('barcode', null);
            $product->quantity = request('quantity', 0);
            $product->unit_price = request('unit_price', 0);
            $product->calories = request('calories', null);
            // $product->vat_code_id = request('vat_code_id', null);
            $product->status = request('status', 0); // available for sale or not
            $product->product_category_id = request('product_category_id', null);
            $product->store_id = request('store_id', null);
            $product->save();

            // Upate translations of the product
            if (request('translations')) {
                $product_transaltions_to_keep = [];
                foreach (request('translations') as $translation) {
                    // if an existing record meets both requirenments in first array, then update it, otherwise create one.
                    $product_translation = ProductTranslation::updateOrCreate(
                        [
                            'id' => $translation['id'],
                            'product_id' => $id,
                        ],
                        [
                            'locale' => $translation['locale'],
                            'name' => $translation['name'] ?? null,
                            'excerpt' => $translation['excerpt'] ?? null,
                            'description' => $translation['description'] ?? null,
                        ],
                    );
                    $product_transaltions_to_keep[] = $product_translation->id;
                }
                // delete any product_translation not included in product_transaltions_to_keep array
                $product->translations()->whereNotIn('id', $product_transaltions_to_keep)->delete();
            }

            // upate images of the product
            $image_ids_to_keep = [];
            if (request('images')) { // delete images that are not included in the coming array
                $image_ids_to_keep = collect(request('images'))->pluck('id')->toArray();
            }

            $product_images_to_delete = $product->images()->whereNotIn('id', $image_ids_to_keep);
            foreach ($product_images_to_delete->get() as $image) {
                if (Storage::disk(getSecondaryStorageDisk())->exists($image->url)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($image->url);
                }
            }
            $product_images_to_delete->delete();

            if (request('product_images') && count(request('product_images')) > 0) {
                $image_labels = request('product_image_labels');
                $products_folder = 'stores/' . $product->store_id . '/products/' . $product->id;
                if (!File::exists(storage_path($products_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($products_folder);
                }
                foreach (request('product_images') as $i => $image) {
                    $thumb_img = $image;
                    $img_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                        $products_folder,
                        $thumb_img,
                        uniqid('product-', true) . '.' . $thumb_img->getClientOriginalExtension(),
                    );
                    // create new product image in the DB
                    $product_image = new ProductImage();
                    $product_image->product_id = $product->id;
                    $product_image->label = $image_labels[$i] ?? null;
                    $product_image->url = $img_path;
                    $product_image->save();
                }
            }

            // $product->availability = ($product->quantity <= 0) ? 'out of stock' : 'in stock';
            // $product->save();


            $product = Product::with([
                'product_offer',
                'product_brand',
                'product_category',
                'translations',
                'images',
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->findOrFail($product->id);

            return response()->json([
                "product" => ProductResource::make($product),
                "message" => __('locale.api.alert.model_updated_successfully', ['model' => 'Product']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:ProductController:update: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'product_id' => 'required|numeric|exists:App\Models\product,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // 1. Find the product
            $product = Product::findOrFail(request('product_id'));

            // 2. Delete images
            $productImages = ProductImage::where('product_id', $product->id)->get();
            foreach ($productImages as $productImg) {
                if (Storage::disk(getSecondaryStorageDisk())->exists($productImg->path)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($productImg->path);
                }
            }

            // 3. delete the product from DB
            $product->delete();

            return response()->json([
                "product" => [],
                "message" => __('locale.api.alert.model_deleted_successfully', ['model' => 'Product']),
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            Log::error('API:ProductController:destroy: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getProductCategories(Request $request)
    {
//        // validate user credentials
//        $error = $this->checkIfRequestHasAuthUser($request);
//        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $store = Store::findOrFail(request('store_id'));

        try {
            $productCategories = ProductCategory::where('business_type_id', $store->business_type_id)
                ->where(function ($q) {
                    $q->where('store_id', request('store_id'))
                        ->orWhereNull('store_id');
                })->orderby('id')
                ->get();

            if ($productCategories->count()) {
                return response()->json([
                    "product_categories" => ProductCategoryCollection::make($productCategories),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getProductCategories: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getFilteredProductCategories(Request $request)
    {
        // Validate user credentials
//        $error = $this->checkIfRequestHasAuthUser($request);
//        if ($error) return $error;

        // Validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
        ]);

        // Redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $storeId = $request->input('store_id');

        try {
            $productCategories = ProductCategory::whereHas('products', function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })->where(function ($q) use ($storeId) {
                $q->where('store_id', $storeId)
                    ->orWhereNull('store_id');
            })->orderby('id')
                ->get();

            if ($productCategories->count()) {
                return response()->json([
                    "product_categories" => ProductCategoryCollection::make($productCategories),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getFilteredProductCategories: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

}
