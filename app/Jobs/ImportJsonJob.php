<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTranslation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ImportJsonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jsonContents;

    public function __construct($jsonContents)
    {
        $this->jsonContents = $jsonContents;
    }

    public function handle()
    {
        $data = json_decode($this->jsonContents, true);

        // Check for JSON errors...
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Invalid JSON data: ' . json_last_error_msg());
            return;
        }

        foreach ($data as $item) {
            try {
                // Add new product
                $product = new Product();
                $product->product_code = $item['product_code'] ?? null;
                $product->model_number = $item['model_number'] ?? null;
                $product->barcode = $item['barcode'] ?? null;
                $product->quantity = $item['quantity'] ?? 0;
                $product->unit_price = $item['unit_price'] ?? 0;
                $product->calories = $item['calories'] ?? null;
                $product->status = $item['status'] ?? 0;
                $product->store_id = 1;
                $product->product_category_id = $item['product_category_id'] ?? null;

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
                        Storage::disk('public')->put($path, $image_base64);

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

            } catch (\Exception $e) {
                Log::error('Error importing product: ' . $e->getMessage());
                // Handle error, possibly emit an event to inform user about the issue...
            }
        }
    }
}