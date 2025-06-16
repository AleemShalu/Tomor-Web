<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Position;
use App\Models\Store;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // create 10 stores
        $faker_en = Faker::create();
        $faker_ar = Faker::create('ar_SA');

        for ($i = 0; $i < 10; $i++) {
            $store = new Store();
            $store->business_type_id = $faker_en->numberBetween(1, 5);
            $store->commercial_name_en = $faker_en->company;
            $store->commercial_name_ar = $faker_ar->company;
            $store->short_name_en = $faker_en->word;
            $store->short_name_ar = $faker_ar->word;
            $store->description_ar = $faker_ar->sentence;
            $store->description_en = $faker_en->sentence;
            $store->email = $faker_en->unique()->email;
            $store->country_id = 1;
            $store->dial_code = 996; // saudi arabia dial code
            $store->contact_no = $faker_en->numerify('5########'); // 9-digit saudi phone number without the leading zero
            $store->tax_id_number = $faker_en->numerify('###############'); // 15-digit saudi tax ID number
            $store->commercial_registration_no = $faker_en->numerify('##########'); // 10-digit saudi commercial registration number
            $store->commercial_registration_expiry = $faker_en->dateTimeBetween('2023-01-01', '2030-12-31')->format('Y-m-d');
            $store->municipal_license_no = $faker_en->numerify('###########'); // 11-digit saudi municipal license number
            $store->created_at = $faker_en->dateTimeThisYear->format('Y-m-d H:i:s');

            // set the owner ID (assuming you have an authenticated user)
            $store->owner_id = 2;

            // save the store record
            $store->save();

            // create a new bank account for the store
            $bankAccount = new BankAccount();
            $bankAccount->account_holder_name = $faker_en->name;
            $bankAccount->iban_number = $faker_en->iban('SA'); // 24-length saudi IBAN
            $bankAccount->store_id = $store->id;
            $bankAccount->save();
        }
    }
}
