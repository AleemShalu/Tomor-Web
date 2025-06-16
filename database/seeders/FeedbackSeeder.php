<?php

namespace Database\Seeders;

use App\Models\ReportSubtype;
use App\Models\ReportType;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultReportTypes = [
            ['ar_name' => 'أقترحات و استفسارات', 'en_name' => 'Feedback', 'code' => 'FB001'],
            ['ar_name' => 'شكوى من العميل إلى المتجر', 'en_name' => 'Customer to Store Complaint', 'code' => 'CA001'],
            ['ar_name' => 'شكوى من العميل إلى النظام', 'en_name' => 'Customer to System Complaint', 'code' => 'CA002'],
            ['ar_name' => 'شكوى من المتجر إلى العميل', 'en_name' => 'Store to Customer Complaint', 'code' => 'CA003'],
            ['ar_name' => 'شكوى من صاحب المتجر إلى النظام', 'en_name' => 'Owner to System Complaint', 'code' => 'CA004'],
            ['ar_name' => 'شكوى من الموظفين إلى النظام', 'en_name' => 'Employee to System Complaint', 'code' => 'CA005'],
            ['ar_name' => 'شكوى خارجية من خارج النظام', 'en_name' => 'External Complaint', 'code' => 'CA006'],
        ];

        foreach ($defaultReportTypes as $type) {
            ReportType::updateOrCreate(
                ['code' => $type['code']],
                ['ar_name' => $type['ar_name'], 'en_name' => $type['en_name']]
            );
        }

        $this->command->info('Default report types inserted or updated.');

        $defaultReportSubTypes = [
            // Feedback
            ['report_type_id' => 1, 'ar_name' => 'استفسار', 'en_name' => 'Inquiry'],
            ['report_type_id' => 1, 'ar_name' => 'تحسين', 'en_name' => 'Improvement'],
            ['report_type_id' => 1, 'ar_name' => 'اقتراح', 'en_name' => 'Suggestion'],
            ['report_type_id' => 1, 'ar_name' => 'فكرة', 'en_name' => 'Idea'],
            ['report_type_id' => 1, 'ar_name' => 'تحسين الأداء', 'en_name' => 'Performance Improvement'],
            ['report_type_id' => 1, 'ar_name' => 'اقتراح للمنتج', 'en_name' => 'Product Suggestion'],
            ['report_type_id' => 2, 'ar_name' => 'مشكلة في الخدمة', 'en_name' => 'Service Issue'],
            ['report_type_id' => 3, 'ar_name' => 'مشكلة في تسجيل الدخول', 'en_name' => 'Login Issue'],
            ['report_type_id' => 4, 'ar_name' => 'شكوى من سلوك العميل', 'en_name' => 'Customer Behavior Complaint'],
            ['report_type_id' => 5, 'ar_name' => 'مشكلة في التسجيل', 'en_name' => 'Registration Issue'],
            ['report_type_id' => 6, 'ar_name' => 'مشكلة في التسجيل', 'en_name' => 'Registration Issue'],
            ['report_type_id' => 7, 'ar_name' => 'مشكلة في التسجيل', 'en_name' => 'Registration Issue'],
        ];

        foreach ($defaultReportSubTypes as $subType) {
            ReportSubtype::updateOrCreate(
                ['report_type_id' => $subType['report_type_id'], 'ar_name' => $subType['ar_name']],
                ['en_name' => $subType['en_name']]
            );
        }

        $this->command->info('Default report subtypes inserted or updated.');
    }
}
