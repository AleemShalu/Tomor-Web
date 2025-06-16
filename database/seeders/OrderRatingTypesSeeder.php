<?php

namespace Database\Seeders;

use App\Models\OrderRatingType;
use Illuminate\Database\Seeder;

class OrderRatingTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $order_rating_types = [
            // 5 or 4 stars
            [
                'code' => 'TIMELY_EXCELLENT_ORT',
                'ar_name' => 'تم توصيل الطلب في الوقت المناسب وبشكل ممتاز',
                'en_name' => 'Delivery was timely and excellent',
            ],
            [
                'code' => 'CONTINUOUS_COMMUNICATION_ORT',
                'ar_name' => 'قام الموظف بالتواصل المستمر وتقديم معلومات دقيقة حول حالة الطلب',
                'en_name' => 'Continuous communication and accurate information provided by the staff',
            ],
            [
                'code' => 'IMMEDIATE_RESPONSE_ORT',
                'ar_name' => 'استجاب بشكل فوري لأي استفسارات أو طلبات إضافية',
                'en_name' => 'Immediate response to any inquiries or additional requests',
            ],
            // 3 stars
            [
                'code' => 'DELAY_IN_COMMUNICATION_ORT',
                'ar_name' => 'حدث بعض التأخير في التواصل/ التوصيل',
                'en_name' => 'Some delay in communication/delivery',
            ],
            [
                'code' => 'OKAY_ARRIVAL_IMPROVE_SERVICE_ORT',
                'ar_name' => 'وصول الطلب بشكل لا بأس به ويجب تحسين الخدمة',
                'en_name' => 'The order arrived okay, but the service needs improvement',
            ],
            [
                'code' => 'IMPROVE_STAFF_RESPONSE_ORT',
                'ar_name' => 'تحسين استجابة الموظف في تقديم المعلومات حول حالة الطلب والاستفسارات',
                'en_name' => 'Improvement needed in staff response regarding order status and inquiries',
            ],
            // 2 or 1 stars
            [
                'code' => 'SIGNIFICANT_DELAY_ORT',
                'ar_name' => 'تأخر كبير في التواصل أو تأخر في التوصيل',
                'en_name' => 'Significant delay in communication or delivery',
            ],
            [
                'code' => 'POOR_ARRIVAL_ORT',
                'ar_name' => 'وصول الطلب بشكل سيئ',
                'en_name' => 'Poor arrival of the order',
            ],
            [
                'code' => 'INCOMPLETE_ARRIVAL_ORT',
                'ar_name' => 'وصول الطلب ناقص',
                'en_name' => 'Incomplete order arrival',
            ],
            [
                'code' => 'INCORRECT_ARRIVAL_ORT',
                'ar_name' => 'وصول طلب خاطئ',
                'en_name' => 'Incorrect order arrival',
            ],
            [
                'code' => 'POOR_STAFF_RESPONSE_ORT',
                'ar_name' => 'استجابة سيئة من الموظف حول حالة الطلب او الاستفسارات',
                'en_name' => 'Poor response from the staff regarding order status or inquiries',
            ],
            // others
            [
                'code' => 'OTHERS_ORT',
                'ar_name' => 'أخرى',
                'en_name' => 'Others',
            ],
        ];

        // Insert or update the data into the 'order_rating_types' table
        foreach ($order_rating_types as $type) {
            OrderRatingType::firstOrCreate(
                ['code' => $type['code']],
                [
                    'ar_name' => $type['ar_name'],
                    'en_name' => $type['en_name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Output success message
        $this->command->info('Order rating types inserted or updated.');
    }
}
