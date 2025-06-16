<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Store;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StoreCustomSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Define the paths to the files in the public folder
        $publicTaxIdAttachmentPath = public_path('fake-data/attachments/tax_id_attachment.pdf');
        $publicCommercialRegAttachmentPath = public_path('fake-data/attachments/commercial_registration_attachment.pdf');
        $publicLogoPath = public_path('fake-data/attachments/logo.png');
        $publicStoreHeaderPath = public_path('fake-data/attachments/store_header.png');
        $publicMenuPdfPath = public_path('fake-data/attachments/menu.pdf');

        // Define the paths where you want to save the files in storage/app/public
        $storageTaxIdAttachmentPath = 'public/fake-data/attachments/tax_id_attachment.pdf';
        $storageCommercialRegAttachmentPath = 'public/fake-data/attachments/commercial_registration_attachment.pdf';
        $storageLogoPath = 'public/fake-data/attachments/logo.png';
        $storageStoreHeaderPath = 'public/fake-data/attachments/store_header.png';
        $storageMenuPdfPath = 'public/fake-data/attachments/menu.pdf';

        // Copy the files from public to storage/app/public if they don't exist
        if (!Storage::exists($storageTaxIdAttachmentPath)) {
            Storage::put($storageTaxIdAttachmentPath, file_get_contents($publicTaxIdAttachmentPath));
        }
        if (!Storage::exists($storageCommercialRegAttachmentPath)) {
            Storage::put($storageCommercialRegAttachmentPath, file_get_contents($publicCommercialRegAttachmentPath));
        }
        if (!Storage::exists($storageLogoPath)) {
            Storage::put($storageLogoPath, file_get_contents($publicLogoPath));
        }
        if (!Storage::exists($storageStoreHeaderPath)) {
            Storage::put($storageStoreHeaderPath, file_get_contents($publicStoreHeaderPath));
        }
        if (!Storage::exists($storageMenuPdfPath)) {
            Storage::put($storageMenuPdfPath, file_get_contents($publicMenuPdfPath));
        }

        // Check if store already exists before creating a new one
        if (!Store::where('email', 'alibaba@example.com')->exists()) {
            $store = new Store();
            $store->business_type_id = 1;
            $store->commercial_name_en = "Ali Baba Nuts";
            $store->commercial_name_ar = "علي بابا للمكسرات";
            $store->short_name_en = "Ali Baba";
            $store->short_name_ar = "علي بابا";
            $store->email = "alibaba@example.com";
            $store->country_id = 1;
            $store->dial_code = "996"; // Example dial code
            $store->contact_no = "123456789"; // Example phone number
            $store->tax_id_number = $faker->numerify('###############'); // 15-digit tax ID number
            $store->commercial_registration_no = $faker->numerify('##########'); // 10-digit registration number
            $store->commercial_registration_expiry = $faker->dateTimeBetween('2024-01-01', '2030-12-31')->format('Y-m-d');
            $store->municipal_license_no = $faker->numerify('###########'); // 11-digit license number
            $store->description_ar = "متجر لبيع المكسرات";
            $store->description_en = "A store selling nuts";
            $store->owner_id = 2;

            // Set other fields to defaults or empty values
            $store->secondary_dial_code = "996"; // Example dial code
            $store->secondary_contact_no = "987456321"; // Example phone number
            $store->tax_id_attachment = $storageTaxIdAttachmentPath;
            $store->commercial_registration_attachment = $storageCommercialRegAttachmentPath;
            $store->api_url = null;
            $store->api_admin_url = null;
            $store->menu_pdf = $storageMenuPdfPath;
            $store->website = null;
            $store->logo = $storageLogoPath;
            $store->store_header = $storageStoreHeaderPath;
            $store->status = 1;

            // Save the store record
            $store->save();

            // Create a new bank account for the store
            $bankAccount = new BankAccount();
            $bankAccount->account_holder_name = "Ali Baba Nuts";
            $bankAccount->iban_number = $faker->iban('SA'); // Assuming you're using IBAN for Saudi Arabia
            $bankAccount->store_id = $store->id;
            $bankAccount->save();

            // Output success message
            $this->command->info('Store and bank account inserted.');
        } else {
            // Output message if store already exists
            $this->command->info('Store with email alibaba@example.com already exists.');
        }
    }
}
