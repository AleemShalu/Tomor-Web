<?php

namespace Database\Seeders;

use App\Models\AppVersion;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppVersion::create([
            'version' => '1.0.0',
            'details_ar' => 'الإصدار الأول.',
            'details_en' => 'Initial release.',
            'release_date' => Carbon::parse('2024-01-01'),
            'is_mandatory' => false,
            'download_url' => 'https://example.com/downloads/1.0.0',
            'platforms' => json_encode(['Android', 'iOS']), // Multiple platforms as an array
            'release_notes_ar' => 'الإصدار الأول للتطبيق مع الميزات الأساسية.',
            'release_notes_en' => 'Initial release of the app with basic features.',
        ]);

        AppVersion::create([
            'version' => '1.0.1',
            'details_ar' => 'إصلاح الأخطاء وتحسين الأداء.',
            'details_en' => 'Bug fixes and performance improvements.',
            'release_date' => Carbon::parse('2024-02-01'),
            'is_mandatory' => false,
            'download_url' => 'https://example.com/downloads/1.0.1',
            'platforms' => json_encode(['Android']), // Only Android platform
            'release_notes_ar' => 'تم إصلاح مشكلة تسجيل الدخول وتحسين سرعة تحميل التطبيق.',
            'release_notes_en' => 'Fixed login issue and improved app loading speed.',
        ]);

        AppVersion::create([
            'version' => '1.0.2',
            'details_ar' => 'تمت إضافة ميزات جديدة.',
            'details_en' => 'New features added.',
            'release_date' => Carbon::parse('2024-03-01'),
            'is_mandatory' => true,
            'download_url' => 'https://example.com/downloads/1.0.2',
            'platforms' => json_encode(['Android', 'iOS']), // Multiple platforms as an array
            'release_notes_ar' => 'تمت إضافة إشعارات الدفع وتعزيز ميزات الأمان.',
            'release_notes_en' => 'Added push notifications and enhanced security features.',
        ]);
    }
}