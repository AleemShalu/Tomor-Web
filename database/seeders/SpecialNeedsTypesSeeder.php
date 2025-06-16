<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialNeedsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'code' => 'HEAR_DT',
                'disability_type_en' => 'Hearing Disability',
                'disability_type_ar' => 'إعاقة سمعية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'VISU_DT',
                'disability_type_en' => 'Visual Disability',
                'disability_type_ar' => 'إعاقة بصرية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'INTL_DT',
                'disability_type_en' => 'Intellectual Disability',
                'disability_type_ar' => 'إعاقة عقلية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PHYS_DT',
                'disability_type_en' => 'Physical Disability',
                'disability_type_ar' => 'إعاقة حركية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($data as $record) {
            // Check if the record with the same 'code' already exists
            $exists = DB::table('special_needs_types')->where('code', $record['code'])->exists();

            if (!$exists) {
                // Insert the record if it does not exist
                DB::table('special_needs_types')->insert($record);
            }
        }

        // Output success message
        $this->command->info('Special needs types inserted.');
    }
}
