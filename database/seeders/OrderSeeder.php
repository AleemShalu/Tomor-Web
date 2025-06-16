<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = FakerFactory::create(); // Initialize the faker

        for ($i = 0; $i < 50; $i++) {
            // Retrieve the last store order
            $lastStoreOrder = Order::where('store_id', $faker->numberBetween(1, 10)) // Modify this to match your actual range of store IDs
            ->orderBy('id', 'desc')
                ->first();

            // Define the list of colors in hex format
            $colors = ['#FF0000', '#FFFF00', '#0000FF', '#00FF00']; // Red, Yellow, Blue, Green

            // Initialize color index (retrieve it from the last order if it exists)
            $colorIndex = 0;

            if ($lastStoreOrder) {
                // Retrieve the color index from the last order's color
                $lastOrderColor = $lastStoreOrder->order_color;
                $colorIndex = array_search($lastOrderColor, $colors);

                // Increment the color index for the next order
                $colorIndex = ($colorIndex + 1) % count($colors);
            }

            // Increment the order numbers
            if ($lastStoreOrder) {
                $orderNumber = $lastStoreOrder->order_number + 1;
            } else {
                // If there are no existing orders, start from 1
                $orderNumber = 1;
            }

            // Determine the color for the new order based on the color index
            $colorIndex = $faker->numberBetween(0, 3);
            $color = $colors[$colorIndex];

            // Create the main order
            $order = Order::create([
                'order_number' => $faker->unique()->randomNumber(3), // Use the generated order number
                'branch_order_number' => 'BRANCH-ORD-' . $faker->unique()->randomNumber(6),
                'branch_queue_number' => 'QUEUE-' . $faker->unique()->randomNumber(3),
                'store_order_number' => 'STORE-ORD-' . $faker->unique()->randomNumber(3), // Update to use STORE-ORD-
                'status' => $faker->randomElement(['received', 'processing', 'delivering', 'delivered', 'canceled', 'unknown']),
                'order_date' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
                'order_color' => $color, // Set the determined color
                'customer_id' => 4, // Modify this to the actual range of customer IDs
                'customer_name' => $faker->name,
                'customer_dial_code' => '966',
                'customer_contact_no' => $faker->unique()->numerify('5########'), // 9-digit number
                'customer_email' => $faker->unique()->safeEmail,
                'customer_vehicle_id' => $faker->numberBetween(1, 1), // Modify this to the actual range of customer vehicle IDs
                'customer_vehicle_description' => $faker->sentence(3),
                'customer_vehicle_manufacturer' => $faker->word, // Replace with actual manufacturer data
                'customer_vehicle_name' => $faker->word, // Replace with actual vehicle name data
                'customer_vehicle_model_year' => $faker->year, // Replace with actual model year data
                'customer_vehicle_color' => $faker->colorName,
                'customer_vehicle_plate_letters' => $faker->lexify('???'), // Replace with actual plate letters data
                'customer_vehicle_plate_number' => $faker->randomNumber(3), // Replace with actual plate number data
                'customer_special_needs_qualified' => $faker->boolean(),
                'items_count' => $faker->numberBetween(1, 10),
                'items_quantity' => $faker->numberBetween(1, 50),
                'exchange_rate' => $faker->randomFloat(4, 0.01, 3.0),
                'conversion_time' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
                'order_currency_code' => $faker->randomElement(['SAR']),
                'base_currency_code' => $faker->randomElement(['SAR']),
                'grand_total' => $faker->randomFloat(4, 200, 1000),
                'base_grand_total' => $faker->randomFloat(4, 200, 1000),
                'sub_total' => $faker->randomFloat(4, 50, 400),
                'base_sub_total' => $faker->randomFloat(4, 50, 400),
                'service_total' => $faker->randomFloat(4, 2, 50),
                'base_service_total' => $faker->randomFloat(4, 2, 50),
                'discount_total' => $faker->randomFloat(4, 0, 100),
                'base_discount_total' => $faker->randomFloat(4, 0, 100),
                'tax_total' => $faker->randomFloat(4, 1, 50),
                'base_tax_total' => $faker->randomFloat(4, 1, 50),
                'taxable_total' => $faker->randomFloat(4, 100, 600),
                'base_taxable_total' => $faker->randomFloat(4, 100, 600),
                'checkout_method' => $faker->randomElement(['card', 'mobile', 'wallet', 'cash on delivery']),
                'coupon_code' => $faker->randomElement([null, 'DISCOUNT50', 'FREESHIPPING']),
                'is_gift' => $faker->boolean(),
                'is_guest' => $faker->optional()->boolean(),
                'store_id' => $faker->numberBetween(1, 1), // Modify this to the actual range of store IDs
                'store_branch_id' => $faker->numberBetween(1, 1), // Modify this to the actual range of store branch IDs
                'employee_id' => 3, // Modify this to the actual range of employee IDs
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
            ]);

            // Define sample items
            $items = [
                [
                    'product_id' => 1, // Replace with the actual product ID
                    'item_name' => 'Sample Item 1',
                    'item_description' => 'Description for Item 1',
                    'item_unit_price' => 10.99, // Replace with the actual price
                    'item_quantity' => 3,
                    'item_status' => 'received', // You can set the initial status here
                    'note' => 'Sample note for Item 1',
                    'voice_url' => 'https://example.com/voice1.mp3',
                    'voice_path' => 'path/to/voice1.mp3',
                ],
                [
                    'product_id' => 2, // Replace with the actual product ID
                    'item_name' => 'Sample Item 2',
                    'item_description' => 'Description for Item 2',
                    'item_unit_price' => 15.99, // Replace with the actual price
                    'item_quantity' => 2,
                    'item_status' => 'received', // You can set the initial status here
                    'note' => 'Sample note for Item 2',
                    'voice_url' => 'https://example.com/voice2.mp3',
                    'voice_path' => 'path/to/voice2.mp3',
                ],
                // add more items as needed
            ];

            // Create order items
            foreach ($items as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'item_name' => $itemData['item_name'],
                    'item_description' => $itemData['item_description'],
                    'item_unit_price' => $itemData['item_unit_price'],
                    'item_quantity' => $itemData['item_quantity'],
                    'item_status' => $itemData['item_status'],
                    'note' => $itemData['note'],
                    'voice_url' => $itemData['voice_url'],
                    'voice_path' => $itemData['voice_path'],
                ]);
            }
        }
    }
}
