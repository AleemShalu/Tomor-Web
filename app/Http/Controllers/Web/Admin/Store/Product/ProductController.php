<?php

namespace App\Http\Controllers\Web\Admin\Store\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id, Request $request)
    {
        $categories = ProductCategory::all(); // Retrieve all categories
        $selectedCategoryId = $request->get('category'); // Get selected category from query parameter
        $searchKeyword = $request->get('search'); // Get search keyword from query parameter

        $productsQuery = Product::with('product_brand', 'product_category', 'translations', 'images')
            ->where('store_id', $id);

        if ($selectedCategoryId) {
            $productsQuery->where('product_category_id', $selectedCategoryId);
        }

        if ($searchKeyword) {
            $productsQuery->whereHas('translations', function ($query) use ($searchKeyword) {
                $query->where('name', 'like', "%{$searchKeyword}%");
            });
        }

        $products = $productsQuery->paginate(13); // Paginate with 13 products per page

        return view('admin.store.products.index', compact('products', 'categories', 'selectedCategoryId', 'searchKeyword', 'id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getProductImageUrl($productId, $index)
    {
        $product = Product::with('images')->find($productId);

        if ($product && isset($product->images[$index])) {
            $imageUrl = asset('storage/' . $product->images[$index]->url);
            return response()->json(['imageUrl' => $imageUrl]);
        }

        return response()->json(['error' => 'Image not found'], 404);
    }

}
