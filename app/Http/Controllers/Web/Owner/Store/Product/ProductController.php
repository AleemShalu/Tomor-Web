<?php

namespace App\Http\Controllers\Web\Owner\Store\Product;

use App\Helpers\FormHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductTranslation;
use App\Models\Store;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $ProductValidationRules = new ProductRequest();
        $validator = Validator::make($request->all(), $ProductValidationRules->rules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // 1. Add new product
            $product = new Product();
            $product->product_code = request('product_code') ? Str::upper(request('product_code')) : null;
            $product->unit_price = request('unit_price', 0);
            $product->product_category_id = request('product_category_id');
            $product->store_id = request('store_id', $request->store_id);
            $product->status = request('status');
            $product->save();

            // 2. Add translations to the product
            if ($request->has('translations')) {
                foreach ($request->input('translations') as $locale => $translation) {
                    $productTranslation = new ProductTranslation();
                    $productTranslation->product_id = $product->id;
                    $productTranslation->locale = $translation['locale'] ?? null;
                    $productTranslation->name = $translation['name'] ?? null;
                    $productTranslation->description = $translation['description'] ?? null;
                    $productTranslation->save();
                }
            }

            // 4. Add product images
            if (request('product_images') && count(request('product_images')) > 0) {
                $image_labels = request('product_image_labels');
                $store_product_folder = 'stores/' . $product->store_id . '/products/' . $product->id;

                if (!Storage::disk(getSecondaryStorageDisk())->exists($store_product_folder)) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_product_folder);
                }

                foreach (request('product_images') as $i => $image) {
                    $filename = uniqid('product-', false) . '.' . $image->getClientOriginalExtension();
                    $img_path = $store_product_folder . '/' . $filename;
                    $outputPath = Storage::disk(getSecondaryStorageDisk())->path($img_path);

                    // Resize the image before saving it
                    $resized = FormHelper::resize(800, $outputPath, $image->getPathname()); // Resize to 800px width

                    if ($resized) {
                        // Create new product image in the DB
                        $product_image = new ProductImage();
                        $product_image->product_id = $product->id;
                        $product_image->label = $image_labels[$i] ?? null;
                        $product_image->url = $img_path;
                        $product_image->save();
                    }
                }
            }

            $product->availability = ($product->quantity <= 0) ? 'out of stock' : 'in stock';
            $product->save();

            $product = Product::with([
                // 'product_type',
                'product_brand',
                'translations',
                'images',
                // 'vat_code',
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->findOrFail($product->id);

//            return response()->json([
//                'product' => ProductResource::make($product),
//                'message' => __('locale.api.alert.model_created_successfully', ['model' => 'Product']),
//            ], Response::HTTP_OK);
            return redirect()->route('products', $request->store_id)->withSuccess('Record created successfully');


        } catch (QueryException $e) {
            Log::error('API:ProductController:store: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Fetch the product with translations and images
        $product = Product::with(['translations', 'images'])->find($id);
        $store = Store::where('id', $product->store_id)->first();

        $this->authorize('manage', $store);

        return view('owner.store.manage.product.show-product', compact('product', 'store'));

    }

    /**
     * Update the specified resource in storage.
     */

    public function edit($id)
    {
        $product = Product::with(['translations', 'images'])->find($id);
        $store = Store::findOrFail($product->store_id);

        $this->authorize('manage', $store);
        $categories = ProductCategory::all()->where('business_type_id', $store->business_type_id || null);

        return view('owner.store.manage.product.update-product', compact('product', 'store', 'categories'));
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            // 'item_type_id' => 'required|numeric|exists:App\Models\ItemType,id',
            'product_brand_id' => 'nullable|numeric',
            'product_code' => [
                'required',
                'alpha_dash',
                'max:50',
            ],
            'model_number' => 'nullable|alpha_dash|max:50',
            'barcode' => 'nullable|alpha_dash|max:50',
            'quantity' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'calories' => 'nullable|string|max:255',
            'status' => [
                'nullable',
                Rule::in([0, 1])
            ],
            // 'vat_code_id' => 'required|numeric|exists:App\Models\VatCode,id',
            'store_id' => 'required|numeric|exists:App\Models\Store,id',

            'translations' => 'required|array|min:0',
            'translations.*.locale' => 'required|distinct|string|max:5',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.excerpt' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string|max:5000',

            'product_images' => 'nullable|array|max:5',
            'product_images.*' => 'present|file|image|mimes:jpg,jpeg,png,heic,raw,webp,bmp,gif|max:520', // up to 5 MB
//            'product_image_labels' => 'nullable|array|max:5',
//            'product_image_labels.*' => 'present|string|min:1|max:50',

            // 'product_stock_in_branches' => 'nullable|array',
            // 'product_stock_in_branches.*.store_branch_id' => 'required_if:product_stock_in_branches,!=,null|nullable|numeric|exists:App\Models\StoreBranch,id',
            // 'product_stock_in_branches.*.stock' => 'required_if:product_stock_in_branches,!=,null|nullable|numeric|min:0',
        ]);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Update the product
            $product = Product::findOrFail($id);
            $product->product_code = request('product_code') ? Str::upper(request('product_code')) : null;
            $product->model_number = request('model_number') ? Str::upper(request('model_number', null)) : null;
            $product->barcode = request('barcode', null);
            $product->quantity = request('quantity', 0);
            $product->unit_price = request('unit_price', 0);
            $product->calories = request('calories', null);
            $product->status = request('status', 0);
            $product->product_category_id = request('product_category_id', 0);
            $product->store_id = request('store_id', $request->store_id);
            $product->save();

            // Update translations
            if (request('translations')) {
                foreach (request('translations') as $locale) {
                    $productTranslation = ProductTranslation::updateOrCreate(
                        ['product_id' => $product->id, 'locale' => $locale['locale']],
                        [
                            'name' => $locale['name'] ?? null,
                            'excerpt' => $locale['excerpt'] ?? null,
                            'description' => $locale['description'] ?? null,
                        ]
                    );
                }
            }
            // Update product images
            if (request('product_images')) {
                $image_labels = request('product_image_labels');
                $store_product_folder = 'stores/' . $product->store_id . '/products/' . $product->id;

                if (!Storage::disk(getSecondaryStorageDisk())->exists($store_product_folder)) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_product_folder);
                }

                $uploadedImages = request('product_images');
                $existingImages = $product->images->pluck('id')->toArray();

                // Loop through existing images and update their labels and URLs
                foreach ($existingImages as $existingImageId) {
                    $index = array_search($existingImageId, $existingImages);
                    if ($index !== false && isset($image_labels[$index])) {
                        $image = ProductImage::find($existingImageId);
                        $image->update(['label' => $image_labels[$index]]);
                    } else {
                        // Delete old image if it's not in the uploaded images
                        $image = ProductImage::find($existingImageId);
                        $image->delete();
                    }
                }

                // Handle new uploaded images with resizing
                foreach ($uploadedImages as $i => $image) {
                    $filename = uniqid('product-', true) . '.' . $image->getClientOriginalExtension();
                    $img_path = $store_product_folder . '/' . $filename;
                    $outputPath = Storage::disk(getSecondaryStorageDisk())->path($img_path);

                    // Resize the image before saving it
                    $resized = FormHelper::resize(800, $outputPath, $image->getPathname()); // Resize to 800px width

                    if ($resized) {
                        // Create new product image in the DB
                        $product_image = ProductImage::create([
                            'product_id' => $product->id,
                            'label' => $image_labels[$i] ?? null,
                            'url' => $img_path,
                        ]);
                    }
                }
            }


            $product->availability = ($product->quantity <= 0) ? 'out of stock' : 'in stock';
            $product->save();

            $product = Product::with([
                // 'product_type',
                'product_brand',
                'translations',
                'images',
                // 'vat_code',
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->findOrFail($product->id);

            return redirect()->route('products', $request->store_id)->withSuccess('Product updated successfully');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            Log::error('API:ProductController:update: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating product',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'product_id' => 'required|numeric|exists:App\Models\Product,id',
        ]);

        // Return JSON response if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
                "success" => false, // Include a success flag
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // 1. Find the product
            $product = Product::findOrFail(request('product_id'));

            // 3. delete the product from DB
            $product->delete();

            // Return a success JSON response
//            return response()->json([
//                'message' => 'Record deleted successfully',
//                'success' => true, // Include a success flag
//            ]);

            return redirect()->route('products', $request->store_id);


        } catch (QueryException $e) {
            Log::error('API:ProductController:destroy: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting product.',
                'success' => false, // Include a success flag
            ], Response::HTTP_BAD_REQUEST);
        }
    }

//    public function destroyAll($id)
//    {
//        // Retrieve the store with the given ID
//        $store = Store::findOrFail($id);
//
//        // Delete all products related to the store
//        $store->products()->delete();
//
//        // Redirect or return a response, depending on your requirements
//        return redirect()->route('product.manage', $id)->withSuccess('Record deleted successfully');
//
//    }

//    public function deleteProductsSelected(Request $request)
//    {
//        // Validate the request to ensure it contains the 'productIds' parameter
//        $request->validate([
//            'productIds' => 'required|array',
//        ]);
//
//        // Extract the product IDs from the request
//        $productIds = $request->input('productIds');
//
//        // Perform the deletion of selected products based on the IDs
//        // Replace 'Product' with your actual Eloquent model for products
//        $deletedProducts = Product::whereIn('id', $productIds)->delete();
//
//        if ($deletedProducts) {
//            // Return a success response
//            return response()->json(['message' => 'Selected products deleted successfully']);
//        } else {
//            // Return an error response
//            return response()->json(['error' => 'Failed to delete selected products'], 500);
//        }
//    }

    public function create($id)
    {
        // Retrieve the store with the given ID
        $store = Store::findOrFail($id);
        $this->authorize('manage', $store);

        $categories = ProductCategory::all()->where('business_type_id', $store->business_type_id);
        // Redirect or return a response, depending on your requirements
        return view('owner.store.manage.product.add-product', compact('store', 'categories'));

    }

    public function importJson(Request $request)
    {
        ini_set('memory_limit', '2565555M');
        $storeId = request('store_id');

        $response = [
            'success' => false,
            'message' => '',
            'data' => [
                'items' => []
            ]
        ];

        // Validate the request
        $validator = Validator::make($request->all(), [
            'json_file' => 'required|file|mimes:json',
            'store_id' => 'required|numeric|exists:stores,id',
        ]);

        if ($validator->fails()) {
            $response['message'] = $validator->errors()->first();
            return response()->json($response, 400);
        }

        // Process the JSON file
        if ($request->hasFile('json_file')) {
            $jsonFile = $request->file('json_file');
            $jsonContents = file_get_contents($jsonFile);
            $data = json_decode($jsonContents, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $response['message'] = 'Invalid JSON file';
                return response()->json($response, 400);
            }

            $importSuccessCount = 0;
            $importErrorCount = 0;

            foreach ($data as $item) {
                try {
                    // Check if category business_type_id matches store's business_type_id
                    $category = ProductCategory::where('code', $item['category_code'])->first();
                    $storeBusinessTypeId = Store::find($request->store_id)->business_type_id;

                    if ($category->business_type_id != $storeBusinessTypeId) {
                        throw new \Exception('Category business type does not match store business type');
                    }

                    // Add new product
                    $product = new Product();
                    $product->product_code = $item['product_code'] ?? null;
                    $product->model_number = $item['model_number'] ?? null;
                    $product->barcode = $item['barcode'] ?? null;
                    $product->quantity = $item['quantity'] ?? 0;
                    $product->unit_price = $item['unit_price'] ?? 0;
                    $product->calories = $item['calories'] ?? null;
                    $product->status = $item['status'] ?? 0;
                    $product->store_id = $storeId;
                    $product->product_category_id = $category->id;

                    // Save the product
                    $product->save();

                    // Add translations to the product
                    if (isset($item['translations'])) {
                        foreach ($item['translations'] as $locale) {
                            $productTranslation = new ProductTranslation();
                            $productTranslation->product_id = $product->id;
                            $productTranslation->locale = $locale['locale'] ?? null;
                            $productTranslation->name = $locale['name'] ?? null;
                            $productTranslation->excerpt = $locale['excerpt'] ?? null;
                            $productTranslation->description = $locale['description'] ?? null;
                            $productTranslation->save();
                        }
                    }

                    // Add product images as base64 encoded data
                    if (isset($item['product_images']) && count($item['product_images']) > 0) {
                        foreach ($item['product_images'] as $imageData) {
                            // Decode the base64 image
                            $image_parts = explode(";base64,", $imageData['image']);
                            $image_type_aux = explode("image/", $image_parts[0]);
                            $image_type = $image_type_aux[1];
                            $image_base64 = base64_decode($image_parts[1]);

                            // Generate a unique name for the image
                            $filename = uniqid('product-', false) . '.' . $image_type;

                            // Path where the image will be saved
                            $store_product_folder = 'stores/' . $product->store_id . '/products/' . $product->id;
                            $path = $store_product_folder . '/' . $filename;

                            // Save the decoded image to storage
                            Storage::disk(getSecondaryStorageDisk())->put($path, $image_base64);

                            // Create new product image in the DB
                            $productImage = new ProductImage();
                            $productImage->product_id = $product->id;
                            $productImage->url = $path; // or 'storage/' . $path depending on your app's setup
                            $productImage->save();
                        }
                    }

                    // Update product availability
                    $product->availability = ($product->quantity <= 0) ? 'out of stock' : 'in stock';
                    $product->save();

                    $importSuccessCount++;
                    $response['data']['items'][] = [
                        'success' => true,
                        'item' => $item
                    ];
                } catch (\Exception $e) {
                    Log::error('Error importing product: ' . $e->getMessage());
                    $errorDetails = 'Product import failed: ' . $e->getMessage();
                    if ($e->getMessage() === 'Category business type does not match store business type') {
                        $errorDetails = 'Category business type does not match store business type';
                    } elseif (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                        $errorDetails = 'Product with code ' . $item['product_code'] . ' already exists for this store';
                    }
                    $response['data']['items'][] = [
                        'success' => false,
                        'item' => $item,
                        'error' => $errorDetails
                    ];
                    $importErrorCount++;
                }
            }

            $response['imported'] = $importSuccessCount;
            $response['failed'] = $importErrorCount;

            if ($importErrorCount === 0) {
                $response['success'] = true;
                $response['message'] = 'Data imported successfully';
                return response()->json($response, 200);
            } else {
                $response['message'] = 'Data import completed with errors';
                return response()->json($response, 200);
            }
        }

        $response['message'] = 'No file provided';
        return response()->json($response, 400);
    }

    public function indexProducts($storeId)
    {
        $store = Store::findOrFail($storeId);
        $this->authorize('manage', $store);

        $products = Product::where('store_id', $storeId)->with([
            'product_offer.offer',
            'product_category',
            'translations'
        ])->get();

        $productsCount = $products->count();

        return view('owner.store.manage.product.index-products', compact('storeId', 'productsCount', 'products'));
    }

    public function destroyImage($id)
    {
        $image = ProductImage::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Delete the image file from storage
        Storage::disk(getSecondaryStorageDisk())->delete($image->url);

        // Delete the image record from the database
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
//    public function fetchProducts(Request $request)
//    {
//        $products = Product::with([
//            'product_offer.offer',
//            'product_category',
//            'translations'
//        ])
//            ->where('store_id', $request->storeId)
//            ->get();
//
//        return response()->json($products);
//    }

}
