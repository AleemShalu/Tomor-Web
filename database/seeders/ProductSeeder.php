<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTranslation;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $faker = Faker::create();

        $productNamesEn = [
            'Roasted Almonds',
            'Cashews Delight',
            'Pistachio Paradise',
            'Mixed Nuts Medley',
            'Honey-Glazed Pecans',
            'Walnut Wonder',
            'Macadamia Magic',
            'Hazelnut Heaven',
            'Brazil Nut Bliss',
            'Nutty Trail Mix',
        ];

        $productNamesAr = [
            'لوز محمص',
            'فستق لذيذ',
            'جوز الهند جنة',
            'مزيج من المكسرات',
            'جوز البقان المشمس بالعسل',
            'جوز الجوز العجيب',
            'المكاديميا السحرية',
            'جنة البندق',
            'فستق البرازيل اللذيذ',
            'مزيج المكسرات اللذيذ',
        ];

        $excerptsEn = [
            'Delightful mix of flavors',
            'Healthy snack option',
            'Perfect for on-the-go snacking',
            'Discover nutty goodness',
            'Burst of energy and nutrition',
            'Satisfy your snack cravings',
            'Irresistibly good nuts',
            'Guilt-free nut treat',
            'Natural goodness of nuts',
            'Perfect blend of nuts and dried fruits',
        ];

        $excerptsAr = [
            'مزيج لذيذ من النكهات',
            'خيار صحي للوجبات الخفيفة',
            'مثالي للوجبات أثناء التنقل',
            'اكتشف لذة الفول السوداني',
            'انفجار من الطاقة والتغذية',
            'راض عن رغباتك في الوجبات الخفيفة',
            'مكسرات لذيذة لا تقاوم',
            'حلوى خالية من الذنب',
            'خير طبيعي من المكسرات',
            'مزيج مثالي من المكسرات والفواكه المجففة',
        ];

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->product_code = strtoupper($faker->unique()->bothify('???-###'));
            $product->model_number = strtoupper($faker->bothify('???-###'));
            $product->barcode = $faker->ean13();
            $product->quantity = $faker->numberBetween(10, 100);
            $product->unit_price = $faker->numberBetween(10, 60);
            $product->calories = $faker->numberBetween(50, 500);
            $product->product_category_id = $faker->numberBetween(1, 5);
            $product->status = $faker->randomElement([0, 1]);
            $product->store_id = 1; // Set your desired store ID
            $product->save(); // Save the product first

            foreach (['en', 'ar'] as $locale) {
                $productTranslation = new ProductTranslation();
                $productTranslation->product_id = $product->id;
                $productTranslation->locale = $locale;
                $productTranslation->name = ($locale == 'en') ? $productNamesEn[$i] : $productNamesAr[$i];
                $productTranslation->excerpt = ($locale == 'en') ? $excerptsEn[$i] : $excerptsAr[$i];
                $productTranslation->description = ($locale == 'en') ? $excerptsEn[$i] : $excerptsAr[$i];
                $productTranslation->save();
            }

            $imageUrls = [
                'https://www.tamata.com/media/catalog/product/cache/d65899a294a8e7b1b602f108a6bde908/6/a/6ad261c2-f3f3-4529-b1d7-8edbaf0e24eb.jpg',
                'https://www.tamata.com/media/catalog/product/cache/d65899a294a8e7b1b602f108a6bde908/a/l/alibaba-18-4-2021-9_1.jpg',
                'https://www.tamata.com/media/catalog/product/cache/d65899a294a8e7b1b602f108a6bde908/5/4/542e0651-1d75-454a-b339-ffffeeb1c01c.jpg',
            ];

            $imageUrl = $imageUrls[$i % count($imageUrls)];

            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.3"
                    )
                )
            );

            $imageContents = file_get_contents($imageUrl, false, $context);

            $store_product_folder = 'stores/' . $product->store_id . '/products/' . $product->id;
            if (!Storage::exists($store_product_folder)) {
                Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_product_folder);
            }

            $extension = pathinfo($imageUrl, PATHINFO_EXTENSION);
            $imageName = 'product-' . Str::uuid()->toString() . '.' . $extension;
            Storage::disk(getSecondaryStorageDisk())->put($store_product_folder . '/' . $imageName, $imageContents);

            $productImage = new ProductImage();
            $productImage->product_id = $product->id;
            $productImage->label = $faker->words(2, true);
            $productImage->url = $store_product_folder . '/' . $imageName;
            $productImage->save();
        }
    }
}
