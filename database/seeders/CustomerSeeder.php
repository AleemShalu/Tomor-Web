<?php

namespace Database\Seeders;

use App\Models\BankCard;
use App\Models\CustomerVehicle;
use App\Models\CustomerWithSpecialNeeds;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = FakerFactory::create(); // Initialize the faker

        $vehicleNames = [
            'Toyota Camry',
            'Honda Accord',
            'Ford Explorer',
            'Chevrolet Malibu',
            'Nissan Altima',
            'Hyundai Sonata',
            'Kia Optima',
            'Mazda 6',
            'Subaru Legacy',
            'Volkswagen Passat',
            'Toyota Corolla',
            'Ford F-150',
            'Honda Civic',
            'Jeep Wrangler',
            'Subaru Outback',
            'Audi A4',
            'Mercedes-Benz C-Class',
            'BMW 3 Series',
            'Lexus RX',
            'Tesla Model 3',
            'Volkswagen Golf',
            'Hyundai Tucson',
            'Kia Sportage',
            'Chevrolet Tahoe',
            'Nissan Rogue',
            'Mazda CX-5',
            'Toyota RAV4',
            'Ford Escape',
            'Jeep Grand Cherokee',
            'Audi Q5',
            'Honda CR-V',
            'Lexus ES',
            'Mercedes-Benz E-Class',
            'BMW 5 Series',
            'Tesla Model Y',
            'Volkswagen Tiguan',
            'Hyundai Santa Fe',
            'Kia Sorento',
            'Chevrolet Silverado',
            'Nissan Sentra',
            'Mazda3',
            'Toyota Highlander',
            'Ford Edge',
            'Jeep Cherokee',
            'Audi Q7',
            'Honda HR-V',
            'Lexus NX',
        ];

        for ($i = 0; $i < 50; $i++) {
            $user_customer = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make(123456789),
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now(),
            ]);

            $user_customer_special_needs = new CustomerWithSpecialNeeds();
            $user_customer_special_needs->customer_id = $user_customer->id;
            $user_customer_special_needs->special_needs_type_id = $faker->numberBetween(1, 4);
            $user_customer_special_needs->special_needs_qualified = $faker->boolean();
            $user_customer_special_needs->special_needs_sa_card_number = $faker->randomDigit();
            $user_customer_special_needs->special_needs_description = $faker->sentence();
            $user_customer_special_needs->special_needs_attachment = null;
            $user_customer_special_needs->special_needs_status = $faker->randomElement(['approved', 'pending', 'reviewing', 'rejected']);
            $user_customer_special_needs->save();

            $user_customer->assignRole('customer');

            // Create a vehicle for each customer with a real vehicle name
            $vehicle = new CustomerVehicle();
            $vehicle->customer_id = $user_customer->id;
            $vehicle->vehicle_manufacturer = $faker->company(); // You can replace this with actual vehicle data
            $vehicle->vehicle_name = $vehicleNames[$i % count($vehicleNames)]; // Cycle through the vehicle names
            $vehicle->vehicle_model_year = $faker->numberBetween(2000, 2023); // You can replace this with actual vehicle data
            $vehicle->vehicle_color = $faker->colorName(); // You can replace this with actual vehicle data
            $vehicle->vehicle_plate_number = $faker->unique()->regexify('[0-9]{4}'); // Generate a 5-digit plate number
            $vehicle->vehicle_plate_letters_ar = implode(' ', $this->generateRandomArabicCombinations(3, 1)); // Convert array to a space-separated string
            $vehicle->vehicle_plate_letters_en = $faker->regexify('[A-Z]{3}'); // Generate two uppercase letters for English
            $vehicle->default_vehicle = $faker->boolean();
            $vehicle->save();

            // Create a bank card for each customer
            $bankCard = new BankCard();
            $bankCard->customer_id = $user_customer->id;
            $bankCard->card_holder_name = $faker->name(); // You can replace this with actual cardholder names
            $bankCard->card_number = $faker->creditCardNumber(); // You can replace this with actual card numbers
            $bankCard->card_expiry_year = $faker->creditCardExpirationDate()->format('Y'); // You can replace this with actual expiration dates
            $bankCard->card_expiry_month = $faker->creditCardExpirationDate()->format('m'); // You can replace this with actual expiration dates
            $bankCard->card_cvv = $faker->randomNumber(3); // You can replace this with actual CVV numbers
            $bankCard->default_card = 0;
            $bankCard->save();
        }
    }

    private function generateRandomArabicCombinations($count, $lettersPerCombination)
    {
        $arabicAlphabet = 'ا.ب.ت.ج.ح.خ.د.ذ.ر.ز.س.ش.ص.ض.ط.ظ.ع.غ.ف.ق.ك.ل.م.ن.هـ.و.ي';

        $combinations = [];

        // Split the Arabic alphabet string into an array of characters
        $alphabetChars = explode('.', $arabicAlphabet);

        for ($i = 0; $i < $count; $i++) {
            $randomCombination = '';

            // Generate a random combination of Arabic letters
            for ($j = 0; $j < $lettersPerCombination; $j++) {
                $randomIndex = rand(0, count($alphabetChars) - 1);
                $randomCombination .= $alphabetChars[$randomIndex];
            }

            $combinations[] = $randomCombination;
        }

        return $combinations;
    }

}