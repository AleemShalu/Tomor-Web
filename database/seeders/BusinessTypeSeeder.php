<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use App\Models\Category;
use App\Models\Position;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // Add default business types
        $businessTypes = [
            ['code' => 'grocery', 'name_ar' => 'بقالة', 'name_en' => 'Grocery'],
            ['code' => 'restaurant', 'name_ar' => 'مطعم', 'name_en' => 'Restaurant'],
            ['code' => 'bakery', 'name_ar' => 'مخبز', 'name_en' => 'Bakery'],
            ['code' => 'cafe', 'name_ar' => 'مقهى', 'name_en' => 'Coffee Shop'],
            ['code' => 'fish_meat', 'name_ar' => 'لحوم وأسماك', 'name_en' => 'Fish and Meat'],
            ['code' => 'honey_and_herbals', 'name_ar' => 'عسل وعطارة', 'name_en' => 'Honey and Herbals'],
            ['code' => 'clothing', 'name_ar' => 'ملابس', 'name_en' => 'Clothing'],
            ['code' => 'electronics', 'name_ar' => 'إلكترونيات', 'name_en' => 'Electronics'],
            ['code' => 'bookstore', 'name_ar' => 'مكتبة', 'name_en' => 'Bookstore'],
            ['code' => 'pharmacy', 'name_ar' => 'صيدلية', 'name_en' => 'Pharmacy'],
            ['code' => 'furniture', 'name_ar' => 'أثاث ومفروشات', 'name_en' => 'Furniture'],
            ['code' => 'jewelry', 'name_ar' => 'مجوهرات', 'name_en' => 'Jewelry'],
            ['code' => 'cosmetics', 'name_ar' => 'مستحضرات التجميل', 'name_en' => 'Cosmetics'],
            ['code' => 'toy_store', 'name_ar' => 'ألعاب', 'name_en' => 'Toy'],
            ['code' => 'equipment', 'name_ar' => 'معدات وآليات', 'name_en' => 'Equipment and Machinery'],
            ['code' => 'home_decor', 'name_ar' => 'الزينة المنزلية', 'name_en' => 'Home Decor'],
            ['code' => 'parts', 'name_ar' => 'قطع الغيار', 'name_en' => 'Spare Parts'],
            ['code' => 'sporting_goods', 'name_ar' => 'سلع رياضية', 'name_en' => 'Sporting Goods'],
            ['code' => 'stationery', 'name_ar' => 'قرطاسية', 'name_en' => 'Stationery'],
            ['code' => 'beauty_salon', 'name_ar' => 'صالون تجميل', 'name_en' => 'Beauty Salon'],
            ['code' => 'optics', 'name_ar' => 'بصريات', 'name_en' => 'Optics'],
            ['code' => 'others', 'name_ar' => 'أخرى', 'name_en' => 'Others'],

            // add more business types as needed
        ];

        foreach ($businessTypes as $business_typeData) {
            // Update or create the business type
            BusinessType::updateOrCreate(
                [
                    'code' => $business_typeData['code']
                ],
                [
                    'name_ar' => $business_typeData['name_ar'],
                    'name_en' => $business_typeData['name_en']
                ]
            );
        }


        foreach ($businessTypes as $businessTypeData) {
            $businessType = BusinessType::updateOrCreate(['code' => $businessTypeData['code']], $businessTypeData);

            // Logic to record categories based on the selected store type
            $categories = [];

            // Populate $categories array based on the selected store type
            if ($businessType->code === 'grocery') {
                $categories = [
                    ['name_ar' => 'فواكه', 'name_en' => 'Fruits', 'code' => 'fruits'],
                    ['name_ar' => 'خضروات', 'name_en' => 'Vegetables', 'code' => 'vegetables'],
                    ['name_ar' => 'منتجات الألبان', 'name_en' => 'Dairy Products', 'code' => 'dairy_products'],
                    ['name_ar' => 'لحوم', 'name_en' => 'Meat', 'code' => 'meat'],
                    ['name_ar' => 'دجاج', 'name_en' => 'Chicken', 'code' => 'chicken'],
                    ['name_ar' => 'أسماك', 'name_en' => 'Fish', 'code' => 'fish'],
                    ['name_ar' => 'ماء', 'name_en' => 'Water', 'code' => 'water'],
                    ['name_ar' => 'مشروبات', 'name_en' => 'Beverages', 'code' => 'beverages'],
                    ['name_ar' => 'حلويات', 'name_en' => 'Sweets', 'code' => 'sweets'],
                    ['name_ar' => 'أطعمة عضوية', 'name_en' => 'Organic Foods', 'code' => 'organic_foods'],
                    ['name_ar' => 'منتجات مجمدة', 'name_en' => 'Frozen Foods', 'code' => 'frozen_foods'],
                    ['name_ar' => 'أدوات مطبخ', 'name_en' => 'Kitchen Utensils', 'code' => 'kitchen_utensils'],
                    ['name_ar' => 'توابل وبهارات', 'name_en' => 'Spices and Seasonings', 'code' => 'spices_seasonings'],
                    ['name_ar' => 'مأكولات جاهزة', 'name_en' => 'Ready Meals', 'code' => 'ready_meals'],
                    ['name_ar' => 'أطعمة صحية', 'name_en' => 'Healthy Foods', 'code' => 'healthy_foods'],
                    ['name_ar' => 'معجنات', 'name_en' => 'Pastries', 'code' => 'pastries'],
                    ['name_ar' => 'أطعمة عالية البروتين', 'name_en' => 'High-Protein Foods', 'code' => 'high_protein_foods'],
                    ['name_ar' => 'منتجات غلوتين مجانية', 'name_en' => 'Gluten-Free Products', 'code' => 'gluten_free_products'],
                    ['name_ar' => 'زيوت وشحوم', 'name_en' => 'Oils and Fats', 'code' => 'oils_fats'],
                    ['name_ar' => 'مكسرات وبذور', 'name_en' => 'Nuts and Seeds', 'code' => 'nuts_seeds'],
                    ['name_ar' => 'منتجات عضوية', 'name_en' => 'Organic Products', 'code' => 'organic_products'],
                    ['name_ar' => 'منتجات طبيعية', 'name_en' => 'Natural Products', 'code' => 'natural_products'],
                    ['name_ar' => 'مشروبات طاقة', 'name_en' => 'Energy Drinks', 'code' => 'energy_drinks'],
                    ['name_ar' => 'صلصات وتتبيلات', 'name_en' => 'Sauces and Dressings', 'code' => 'sauces_dressings'],
                    ['name_ar' => 'أطعمة معلبة', 'name_en' => 'Canned Foods', 'code' => 'canned_foods'],
                    ['name_ar' => 'منتجات صحية', 'name_en' => 'Health Products', 'code' => 'health_products'],
                    ['name_ar' => 'شوكولاتة وحلويات', 'name_en' => 'Chocolates and Sweets', 'code' => 'chocolates_sweets'],
                    ['name_ar' => 'منتجات عالية الجودة', 'name_en' => 'Premium Quality Products', 'code' => 'premium_quality_products'],
                    ['name_ar' => 'بقوليات', 'name_en' => 'Legumes', 'code' => 'legumes'],
                    ['name_ar' => 'مشروبات ساخنة', 'name_en' => 'Hot Beverages', 'code' => 'hot_beverages'],
                    ['name_ar' => 'أدوات تحضير القهوة', 'name_en' => 'Coffee Making Tools', 'code' => 'coffee_making_tools'],
                    ['name_ar' => 'منتجات نباتية', 'name_en' => 'Plant-Based Products', 'code' => 'plant_based_products'],
                    ['name_ar' => 'أطعمة جاهزة', 'name_en' => 'Ready-to-Eat Foods', 'code' => 'ready_to_eat_foods'],
                    ['name_ar' => 'معكرونة وشعيرية', 'name_en' => 'Pasta and Noodles', 'code' => 'pasta_noodles'],
                    ['name_ar' => 'أطعمة بحرية', 'name_en' => 'Seafood', 'code' => 'seafood'],
                    ['name_ar' => 'منتجات صحية للأطفال', 'name_en' => 'Healthy Kids Products', 'code' => 'healthy_kids_products'],
                    ['name_ar' => 'أغذية مجمدة', 'name_en' => 'Frozen Food', 'code' => 'frozen_food'],
                    ['name_ar' => 'بيتزا وفطائر', 'name_en' => 'Pizza and Pies', 'code' => 'pizza_pies'],
                    ['name_ar' => 'أطعمة عالية الألياف', 'name_en' => 'High-Fiber Foods', 'code' => 'high_fiber_foods'],
                    ['name_ar' => 'منتجات خالية من السكر', 'name_en' => 'Sugar-Free Products', 'code' => 'sugar_free_products'],
                    ['name_ar' => 'تحلية القهوة', 'name_en' => 'Coffee Sweeteners', 'code' => 'coffee_sweeteners'],
                    ['name_ar' => 'منتجات للريجيم', 'name_en' => 'Diet Products', 'code' => 'diet_products'],
                    ['name_ar' => 'مشروبات غازية', 'name_en' => 'Soft Drinks', 'code' => 'soft_drinks'],
                    ['name_ar' => 'أطعمة خالية من الدهون', 'name_en' => 'Fat-Free Foods', 'code' => 'fat_free_foods'],
                ];
            } elseif ($businessType->code === 'restaurant') {
                $categories = [
                    ['name_ar' => 'مقبلات', 'name_en' => 'Appetizers', 'code' => 'appetizers'],
                    ['name_ar' => 'أطباق رئيسية', 'name_en' => 'Main Courses', 'code' => 'main_courses'],
                    ['name_ar' => 'أطباق جانبية', 'name_en' => 'Side Dishes', 'code' => 'side_dishes'],
                    ['name_ar' => 'حلويات', 'name_en' => 'Desserts', 'code' => 'desserts'],
                    ['name_ar' => 'شوربات', 'name_en' => 'Soups', 'code' => 'soups'],
                    ['name_ar' => 'سلطات', 'name_en' => 'Salads', 'code' => 'salads'],
                    ['name_ar' => 'ماء', 'name_en' => 'Water', 'code' => 'water'],
                    ['name_ar' => 'مشروبات', 'name_en' => 'Beverages', 'code' => 'beverages'],
                    ['name_ar' => 'مشروبات ساخنة', 'name_en' => 'Hot Beverages', 'code' => 'hot_beverages'],
                    ['name_ar' => 'مشروبات باردة', 'name_en' => 'Cold Beverages', 'code' => 'cold_beverages'],
                    ['name_ar' => 'سندويشات', 'name_en' => 'Sandwiches', 'code' => 'sandwiches'],
                    ['name_ar' => 'وجبات سريعة', 'name_en' => 'Fast Food', 'code' => 'fast_food'],
                    ['name_ar' => 'أطباق بحرية', 'name_en' => 'Seafood', 'code' => 'seafood'],
                    ['name_ar' => 'معجنات', 'name_en' => 'Pastries', 'code' => 'pastries'],
                    ['name_ar' => 'لحوم', 'name_en' => 'Meat', 'code' => 'meat'],
                    ['name_ar' => 'مشويات', 'name_en' => 'Grills', 'code' => 'grills'],
                    ['name_ar' => 'وجبات نباتية', 'name_en' => 'Vegetarian Meals', 'code' => 'vegetarian_meals'],
                    ['name_ar' => 'وجبات مشتركة', 'name_en' => 'Combo Meals', 'code' => 'combo_meals'],
                    ['name_ar' => 'وجبات خفيفة', 'name_en' => 'Snacks', 'code' => 'snacks'],
                    ['name_ar' => 'مأكولات عربية', 'name_en' => 'Arabic Cuisine', 'code' => 'arabic_cuisine'],
                    ['name_ar' => 'مأكولات آسيوية', 'name_en' => 'Asian Cuisine', 'code' => 'asian_cuisine'],
                    ['name_ar' => 'مأكولات إيطالية', 'name_en' => 'Italian Cuisine', 'code' => 'italian_cuisine'],
                    ['name_ar' => 'مأكولات مكسيكية', 'name_en' => 'Mexican Cuisine', 'code' => 'mexican_cuisine'],
                    ['name_ar' => 'مأكولات هندية', 'name_en' => 'Indian Cuisine', 'code' => 'indian_cuisine'],
                    ['name_ar' => 'مأكولات يابانية', 'name_en' => 'Japanese Cuisine', 'code' => 'japanese_cuisine'],
                    ['name_ar' => 'مأكولات صينية', 'name_en' => 'Chinese Cuisine', 'code' => 'chinese_cuisine'],
                    ['name_ar' => 'مأكولات تايلاندية', 'name_en' => 'Thai Cuisine', 'code' => 'thai_cuisine'],
                    ['name_ar' => 'مأكولات فرنسية', 'name_en' => 'French Cuisine', 'code' => 'french_cuisine'],
                    ['name_ar' => 'مأكولات إسبانية', 'name_en' => 'Spanish Cuisine', 'code' => 'spanish_cuisine'],
                    ['name_ar' => 'مأكولات يونانية', 'name_en' => 'Greek Cuisine', 'code' => 'greek_cuisine'],
                    ['name_ar' => 'مأكولات تركية', 'name_en' => 'Turkish Cuisine', 'code' => 'turkish_cuisine'],
                    ['name_ar' => 'مأكولات إندونيسية', 'name_en' => 'Indonesian Cuisine', 'code' => 'indonesian_cuisine'],
                    ['name_ar' => 'مأكولات كورية', 'name_en' => 'Korean Cuisine', 'code' => 'korean_cuisine'],
                ];
            } elseif ($businessType->code === 'bakery') {
                $categories = [
                    ['name_ar' => 'الخبز', 'name_en' => 'Breads', 'code' => 'breads'],
                    ['name_ar' => 'المعجنات', 'name_en' => 'Pastries', 'code' => 'pastries'],
                    ['name_ar' => 'الكعك', 'name_en' => 'Cakes', 'code' => 'cakes'],
                    ['name_ar' => 'الحلويات', 'name_en' => 'Sweets', 'code' => 'sweets'],
                    ['name_ar' => 'الكروسون', 'name_en' => 'Croissants', 'code' => 'croissants'],
                    ['name_ar' => 'الدونات', 'name_en' => 'Donuts', 'code' => 'donuts'],
                    ['name_ar' => 'البسكويت', 'name_en' => 'Cookies', 'code' => 'cookies'],
                    ['name_ar' => 'التارت', 'name_en' => 'Tarts', 'code' => 'tarts'],
                    ['name_ar' => 'المعكرون', 'name_en' => 'Macarons', 'code' => 'macarons'],
                    ['name_ar' => 'البيتزا', 'name_en' => 'Pizza', 'code' => 'pizza'],
                    ['name_ar' => 'الفطائر', 'name_en' => 'Pies', 'code' => 'pies'],
                    ['name_ar' => 'الرقائق', 'name_en' => 'Pastry Sheets', 'code' => 'pastry_sheets'],
                    ['name_ar' => 'الشوكولاتة', 'name_en' => 'Chocolates', 'code' => 'chocolates'],
                    ['name_ar' => 'الكب كيك', 'name_en' => 'Cupcakes', 'code' => 'cupcakes'],
                    ['name_ar' => 'الخبز العربي', 'name_en' => 'Arabic Breads', 'code' => 'arabic_breads'],
                    ['name_ar' => 'الخبز الفرنسي', 'name_en' => 'French Breads', 'code' => 'french_breads'],
                    ['name_ar' => 'الخبز الإيطالي', 'name_en' => 'Italian Breads', 'code' => 'italian_breads'],
                    ['name_ar' => 'الخبز الألماني', 'name_en' => 'German Breads', 'code' => 'german_breads'],
                    ['name_ar' => 'الخبز الهندي', 'name_en' => 'Indian Breads', 'code' => 'indian_breads'],
                ];

            } elseif ($businessType->code === 'cafe') {
                $categories = [
                    ['name_ar' => 'حلويات', 'name_en' => 'Desserts', 'code' => 'desserts'],
                    ['name_ar' => 'ماء', 'name_en' => 'Water', 'code' => 'water'],
                    ['name_ar' => 'مشروبات', 'name_en' => 'Beverages', 'code' => 'beverages'],
                    ['name_ar' => 'مشروبات ساخنة', 'name_en' => 'Hot Beverages', 'code' => 'hot_beverages'],
                    ['name_ar' => 'مشروبات باردة', 'name_en' => 'Cold Beverages', 'code' => 'cold_beverages'],
                    ['name_ar' => 'سندويشات', 'name_en' => 'Sandwiches', 'code' => 'sandwiches'],
                    ['name_ar' => 'معجنات', 'name_en' => 'Pastries', 'code' => 'pastries'],
                    ['name_ar' => 'وجبات مشتركة', 'name_en' => 'Combo Meals', 'code' => 'combo_meals'],
                    ['name_ar' => 'وجبات خفيفة', 'name_en' => 'Snacks', 'code' => 'snacks'],
                    ['name_ar' => 'قهوة', 'name_en' => 'Coffee', 'code' => 'coffee'],
                    ['name_ar' => 'شاي', 'name_en' => 'Tea', 'code' => 'tea'],
                    ['name_ar' => 'عصائر طازجة', 'name_en' => 'Fresh Juices', 'code' => 'fresh_juices'],
                    ['name_ar' => 'عصائر مثلجة', 'name_en' => 'Smoothies', 'code' => 'smoothies'],
                    ['name_ar' => 'مشروبات رياضية', 'name_en' => 'Sports Drinks', 'code' => 'sports_drinks'],
                    ['name_ar' => 'مشروبات طاقة', 'name_en' => 'Energy Drinks', 'code' => 'energy_drinks'],
                    ['name_ar' => 'مشروبات مثلجة', 'name_en' => 'Iced Drinks', 'code' => 'iced_drinks'],
                ];

            } elseif ($businessType->code === 'fish_meat') {
                $categories = [
                    ['name_ar' => 'لحوم', 'name_en' => 'Meat', 'code' => 'meat'],
                    ['name_ar' => 'دجاج', 'name_en' => 'Chicken', 'code' => 'chicken'],
                    ['name_ar' => 'أسماك', 'name_en' => 'Fish', 'code' => 'fish'],
                    ['name_ar' => 'أخرى', 'name_en' => 'Other', 'code' => 'other'],
                ];
            } elseif ($businessType->code === 'honey_and_herbals') {
                $categories = [
                    ['name_ar' => 'عسل', 'name_en' => 'Honey', 'code' => 'honey'],
                    ['name_ar' => 'أعشاب', 'name_en' => 'Herbs', 'code' => 'herbs'],
                    ['name_ar' => 'توابل وبهارات', 'name_en' => 'Spices', 'code' => 'spices'],
                    ['name_ar' => 'مكسرات', 'name_en' => 'Nuts', 'code' => 'nuts'],
                    ['name_ar' => 'حلويات', 'name_en' => 'Desserts', 'code' => 'desserts'],
                    ['name_ar' => 'منتجات العناية الشخصية', 'name_en' => 'Personal Care Products', 'code' => 'personal_care'],
                    ['name_ar' => 'أخرى', 'name_en' => 'Other', 'code' => 'other'],
                ];

            } elseif ($businessType->code === 'clothing') {
                $categories = [
                    ['name_ar' => 'ملابس رجالية', 'name_en' => "Men's Clothing", 'code' => 'mens_clothing'],
                    ['name_ar' => 'ملابس نسائية', 'name_en' => "Women's Clothing", 'code' => 'womens_clothing'],
                    ['name_ar' => 'إكسسوارات', 'name_en' => 'Accessories', 'code' => 'accessories'],
                    ['name_ar' => 'فساتين', 'name_en' => 'Dresses', 'code' => 'dresses'],
                    ['name_ar' => 'بلوزات', 'name_en' => 'Blouses', 'code' => 'blouses'],
                    ['name_ar' => 'قمصان', 'name_en' => 'Shirts', 'code' => 'shirts'],
                    ['name_ar' => 'بناطيل', 'name_en' => 'Pants', 'code' => 'pants'],
                    ['name_ar' => 'جينز', 'name_en' => 'Jeans', 'code' => 'jeans'],
                    ['name_ar' => 'تنانير', 'name_en' => 'Skirts', 'code' => 'skirts'],
                    ['name_ar' => 'ملابس رياضية', 'name_en' => 'Sportswear', 'code' => 'sportswear'],
                    ['name_ar' => 'ملابس سباحة', 'name_en' => 'Swimwear', 'code' => 'swimwear'],
                    ['name_ar' => 'ملابس داخلية', 'name_en' => 'Underwear', 'code' => 'underwear'],
                    ['name_ar' => 'ملابس أطفال', 'name_en' => "Children's Clothing", 'code' => 'childrens_clothing'],
                    ['name_ar' => 'ملابس رضع', 'name_en' => 'Baby Clothing', 'code' => 'baby_clothing'],
                    ['name_ar' => 'أحذية', 'name_en' => 'Shoes', 'code' => 'shoes'],
                    ['name_ar' => 'حقائب', 'name_en' => 'Bags', 'code' => 'bags'],
                    ['name_ar' => 'مجوهرات', 'name_en' => 'Jewelry', 'code' => 'jewelry'],
                    ['name_ar' => 'ساعات', 'name_en' => 'Watches', 'code' => 'watches'],
                    ['name_ar' => 'نظارات', 'name_en' => 'Eyewear', 'code' => 'eyewear'],
                    ['name_ar' => 'قبعات', 'name_en' => 'Hats', 'code' => 'hats'],
                    ['name_ar' => 'أوشحة', 'name_en' => 'Scarves', 'code' => 'scarves'],
                    ['name_ar' => 'قفازات', 'name_en' => 'Gloves', 'code' => 'gloves'],
                    ['name_ar' => 'جوارب', 'name_en' => 'Socks', 'code' => 'socks'],
                    ['name_ar' => 'أحزمة', 'name_en' => 'Belts', 'code' => 'belts'],
                    ['name_ar' => 'تيشيرتات', 'name_en' => 'T-shirts', 'code' => 'tshirts'],
                    ['name_ar' => 'سترات', 'name_en' => 'Jackets', 'code' => 'jackets'],
                    ['name_ar' => 'معاطف', 'name_en' => 'Coats', 'code' => 'coats'],
                    ['name_ar' => 'ملابس محافظة', 'name_en' => 'Conservative Clothing', 'code' => 'conservative_clothing'],
                    ['name_ar' => 'ملابس عصرية', 'name_en' => 'Trendy Clothing', 'code' => 'trendy_clothing'],
                    ['name_ar' => 'ملابس فاخرة', 'name_en' => 'Luxury Clothing', 'code' => 'luxury_clothing'],
                    ['name_ar' => 'ملابس رسمية', 'name_en' => 'Formal Wear', 'code' => 'formal_wear'],
                ];
            } elseif ($businessType->code === 'electronics') {
                $categories = [
                    ['name_ar' => 'أجهزة الكمبيوتر', 'name_en' => 'Computers', 'code' => 'computers'],
                    ['name_ar' => 'الهواتف المحمولة', 'name_en' => 'Mobile Phones', 'code' => 'mobile_phones'],
                    ['name_ar' => 'التلفزيونات', 'name_en' => 'Televisions', 'code' => 'televisions'],
                    ['name_ar' => 'أجهزة الصوت والفيديو', 'name_en' => 'Audio & Video Devices', 'code' => 'audio_video_devices'],
                    ['name_ar' => 'الأجهزة اللوحية', 'name_en' => 'Tablets', 'code' => 'tablets'],
                    ['name_ar' => 'اللابتوبات', 'name_en' => 'Laptops', 'code' => 'laptops'],
                    ['name_ar' => 'الطابعات', 'name_en' => 'Printers', 'code' => 'printers'],
                    ['name_ar' => 'الشاشات', 'name_en' => 'Monitors', 'code' => 'monitors'],
                    ['name_ar' => 'الألعاب الإلكترونية', 'name_en' => 'Video Games', 'code' => 'video_games'],
                    ['name_ar' => 'الكاميرات', 'name_en' => 'Cameras', 'code' => 'cameras'],
                    ['name_ar' => 'أجهزة التوجيه', 'name_en' => 'Routers', 'code' => 'routers'],
                    ['name_ar' => 'أجهزة الشبكات', 'name_en' => 'Network Devices', 'code' => 'network_devices'],
                    ['name_ar' => 'مكونات الكمبيوتر', 'name_en' => 'Computer Components', 'code' => 'computer_components'],
                    ['name_ar' => 'أجهزة التخزين', 'name_en' => 'Storage Devices', 'code' => 'storage_devices'],
                    ['name_ar' => 'أجهزة الصوت', 'name_en' => 'Audio Devices', 'code' => 'audio_devices'],
                    ['name_ar' => 'أجهزة الفيديو', 'name_en' => 'Video Devices', 'code' => 'video_devices'],
                    ['name_ar' => 'أجهزة الإضاءة', 'name_en' => 'Lighting Devices', 'code' => 'lighting_devices'],
                    ['name_ar' => 'الأجهزة الذكية', 'name_en' => 'Smart Devices', 'code' => 'smart_devices'],
                    ['name_ar' => 'أجهزة التحكم عن بعد', 'name_en' => 'Remote Controls', 'code' => 'remote_controls'],
                    ['name_ar' => 'أجهزة الطاقة', 'name_en' => 'Power Devices', 'code' => 'power_devices'],
                    ['name_ar' => 'أجهزة الأمان', 'name_en' => 'Security Devices', 'code' => 'security_devices'],
                    ['name_ar' => 'أجهزة التسجيل', 'name_en' => 'Recording Devices', 'code' => 'recording_devices'],
                    ['name_ar' => 'أجهزة الشحن', 'name_en' => 'Charging Devices', 'code' => 'charging_devices'],
                    ['name_ar' => 'الأجهزة المنزلية الذكية', 'name_en' => 'Smart Home Devices', 'code' => 'smart_home_devices'],
                    ['name_ar' => 'أجهزة الكترونية محمولة', 'name_en' => 'Portable Electronics', 'code' => 'portable_electronics'],
                    ['name_ar' => 'ملحقات الإلكترونيات', 'name_en' => 'Electronics Accessories', 'code' => 'electronics_accessories'],
                    ['name_ar' => 'أجهزة الاتصالات', 'name_en' => 'Communication Devices', 'code' => 'communication_devices'],
                    ['name_ar' => 'أجهزة العرض', 'name_en' => 'Display Devices', 'code' => 'display_devices'],
                    ['name_ar' => 'أجهزة الشبكات اللاسلكية', 'name_en' => 'Wireless Network Devices', 'code' => 'wireless_network_devices'],
                    ['name_ar' => 'أجهزة التحكم في المنزل', 'name_en' => 'Home Control Devices', 'code' => 'home_control_devices'],
                ];
            } elseif ($businessType->code === 'bookstore') {
                $categories = [
                    ['name_ar' => 'روايات', 'name_en' => 'Novels', 'code' => 'novels'],
                    ['name_ar' => 'مجلدات', 'name_en' => 'Volumes', 'code' => 'volumes'],
                    ['name_ar' => 'كتب', 'name_en' => 'Books', 'code' => 'books'],
                    ['name_ar' => 'أوراق علمية', 'name_en' => 'Scientific Papers', 'code' => 'scientific_papers'],
                    ['name_ar' => 'كتيبات', 'name_en' => 'Brochures', 'code' => 'brochures'],
                    ['name_ar' => 'مجلات', 'name_en' => 'Magazines', 'code' => 'magazines'],
                    ['name_ar' => 'كتب الأطفال', 'name_en' => 'Children\'s Books', 'code' => 'childrens_books'],
                    ['name_ar' => 'أخرى', 'name_en' => 'Other', 'code' => 'other'],
                ];
            } elseif ($businessType->code === 'pharmacy') {
                $categories = [
                    ['name_ar' => 'أدوية', 'name_en' => 'Medications', 'code' => 'medications'],
                    ['name_ar' => 'مسكنات', 'name_en' => 'Painkillers', 'code' => 'painkillers'],
                    ['name_ar' => 'مضادات حيوية', 'name_en' => 'Antibiotics', 'code' => 'antibiotics'],
                    ['name_ar' => 'شراب', 'name_en' => 'Syrup', 'code' => 'syrup'],
                    ['name_ar' => 'مكملات غذائية', 'name_en' => 'Food supplements', 'code' => 'food_supplements'],
                    ['name_ar' => 'حليب الأطفال', 'name_en' => 'Baby Milk', 'code' => 'baby_milk'],
                    ['name_ar' => 'منتجات العناية الشخصية', 'name_en' => 'Personal Care Products', 'code' => 'personal_care_products'],
                    ['name_ar' => 'معدات طبية', 'name_en' => 'Medical Equipment', 'code' => 'medical_equipment'],
                    ['name_ar' => 'أخرى', 'name_en' => 'Other', 'code' => 'other'],
                ];
            } elseif ($businessType->code === 'furniture') {
                $categories = [
                    ['name_ar' => 'أثاث غرفة المعيشة', 'name_en' => 'Living Room Furniture', 'code' => 'living_room'],
                    ['name_ar' => 'أثاث غرفة النوم', 'name_en' => 'Bedroom Furniture', 'code' => 'bedroom'],
                    ['name_ar' => 'أثاث خارجي', 'name_en' => 'Outdoor Furniture', 'code' => 'outdoor'],
                    ['name_ar' => 'كراسي', 'name_en' => 'Chairs', 'code' => 'chairs'],
                    ['name_ar' => 'طاولات', 'name_en' => 'Tables', 'code' => 'tables'],
                    ['name_ar' => 'أريكة', 'name_en' => 'Sofas', 'code' => 'sofas'],
                ];

            } elseif ($businessType->code === 'jewelry') {
                $categories = [
                    ['name_ar' => 'الخواتم', 'name_en' => 'Rings', 'code' => 'rings'],
                    ['name_ar' => 'القلادات', 'name_en' => 'Necklaces', 'code' => 'necklaces'],
                    ['name_ar' => 'الأقراط', 'name_en' => 'Earrings', 'code' => 'earrings'],
                ];

            } elseif ($businessType->code === 'equipment') {
                $categories = [
                    ['name_ar' => 'آليات', 'name_en' => 'Machinery', 'code' => 'machinery'],
                    ['name_ar' => 'آليات ثقيلة', 'name_en' => 'Heavy Machinery', 'code' => 'heavy_machinery'],
                    ['name_ar' => 'أدوات السباكة', 'name_en' => 'Plumbing Equipment', 'code' => 'plumbing_equipment'],
                    ['name_ar' => 'أدوات كهربائية', 'name_en' => 'Electrical Equipment', 'code' => 'electrical_equipment'],
                    ['name_ar' => 'أدوات الطاقة', 'name_en' => 'Power Equipment', 'code' => 'power_equipment'],
                    ['name_ar' => 'أدوات يدوية', 'name_en' => 'Hand Tools', 'code' => 'hand_tools'],
                    ['name_ar' => 'أدوات النجارة', 'name_en' => 'Woodworking Equipment', 'code' => 'woodworking_equipment'],
                    ['name_ar' => 'مواد البناء', 'name_en' => 'Building Materials', 'code' => 'building_materials'],
                ];

            } elseif ($businessType->code === 'cosmetics') {
                $categories = [
                    ['name_ar' => 'مستحضرات التجميل', 'name_en' => 'Makeup', 'code' => 'makeup'],
                    ['name_ar' => 'مستحضرات العناية بالبشرة', 'name_en' => 'Skincare', 'code' => 'skincare'],
                    ['name_ar' => 'عطورات', 'name_en' => 'Perfumes', 'code' => 'perfumes'],
                ];
            } elseif ($businessType->code === 'parts') {
                $categories = [
                    ['name_ar' => 'قطع غيار السيارات', 'name_en' => 'Car Parts', 'code' => 'car_parts'],
                    ['name_ar' => 'ملحقات السيارات', 'name_en' => 'Car Accessories', 'code' => 'car_accessories'],
                    ['name_ar' => 'منتجات العناية بالسيارة', 'name_en' => 'Car Care Products', 'code' => 'car_care'],
                ];
            } elseif ($businessType->code === 'sporting_goods') {
                $categories = [
                    ['name_ar' => 'معدات اللياقة البدنية', 'name_en' => 'Fitness Equipment', 'code' => 'fitness_equipment'],
                    ['name_ar' => 'رياضات خارجية', 'name_en' => 'Outdoor Sports', 'code' => 'outdoor_sports'],
                    ['name_ar' => 'رياضات جماعية', 'name_en' => 'Team Sports', 'code' => 'team_sports'],
                ];
            } elseif ($businessType->code === 'home_decor') {
                $categories = [
                    ['name_ar' => 'ديكور الجدران', 'name_en' => 'Wall Decor', 'code' => 'wall_decor'],
                    ['name_ar' => 'إضاءة', 'name_en' => 'Lighting', 'code' => 'lighting'],
                    ['name_ar' => 'تحف المنزل', 'name_en' => 'Home Accents', 'code' => 'home_accents'],
                ];
            } elseif ($businessType->code === 'stationery') {
                $categories = [
                    ['name_ar' => 'أقلام', 'name_en' => 'Pens', 'code' => 'pens'],
                    ['name_ar' => 'أوراق', 'name_en' => 'Papers', 'code' => 'papers'],
                    ['name_ar' => 'كراسات ودفاتر', 'name_en' => 'Notebooks', 'code' => 'notebooks'],
                    ['name_ar' => 'مستلزمات الطباعة', 'name_en' => 'Printing Supplies', 'code' => 'printing_supplies'],
                    ['name_ar' => 'مستلزمات الرسم', 'name_en' => 'Art Supplies', 'code' => 'art_supplies'],
                ];
            } elseif ($businessType->code === 'beauty_salon') {
                $categories = [
                    ['name_ar' => 'مستحضرات العناية بالشعر', 'name_en' => 'Hair Care', 'code' => 'hair_care'],
                    ['name_ar' => 'مستحضرات العناية بالأظافر', 'name_en' => 'Nail Care', 'code' => 'nail_care'],
                    ['name_ar' => 'خدمات السبا', 'name_en' => 'Spa Services', 'code' => 'spa_services'],
                ];
            } elseif ($businessType->code === 'toy_store') {
                $categories = [
                    ['name_ar' => 'شخصيات الأكشن', 'name_en' => 'Action Figures', 'code' => 'action_figures'],
                    ['name_ar' => 'الألغاز', 'name_en' => 'Puzzles', 'code' => 'puzzles'],
                    ['name_ar' => 'الألعاب الخارجية', 'name_en' => 'Outdoor Toys', 'code' => 'outdoor_toys'],
                ];
            }

            foreach ($categories as $category) {
                // Update or create the category
                ProductCategory::updateOrCreate(
                    [
                        'business_type_id' => $businessType->id,
                        'code' => $category['code']
                    ],
                    [
                        'category_ar' => $category['name_ar'], // Set the Arabic category name
                        'category_en' => $category['name_en']  // Set the English category name
                    ]
                );
            }

        }


        // output success message
        $this->command->info('Default Business Type inserted.');
    }
}
