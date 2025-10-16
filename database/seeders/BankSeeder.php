<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bankNames = [
            [
              'bank_name' => 'Al Arafah Bank',
            ],
            [
              'bank_name' => 'Brac Bank',
            ],
            [
              'bank_name' => 'Dhaka Bank',
            ],
            [
              'bank_name' => 'Islamic Bank',
            ],
            [
              'bank_name' => 'Jamuna Bank',
            ],
            [
              'bank_name' => 'Madhumoti Bank',
            ],
            [
              'bank_name' => 'NCC Bank',
            ],
            [
              'bank_name' => 'Uttara Bank',
            ],
        ];
   
        // Create Bank Name
        for ($i = 0; $i < count($bankNames); $i++) {
            Bank::create([
                'user_id' => 1,
                'bank_name' => $bankNames[$i]['bank_name']
            ]);
        }
    }
}
