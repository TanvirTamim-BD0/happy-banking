<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MobileWallet;

class MobileWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mobileWallet = [
            [
              'mobile_wallet_name' => 'Bkash',
            ],
            [
              'mobile_wallet_name' => 'Nagad',
            ],
            [
              'mobile_wallet_name' => 'Rocket',
            ],
            [
              'mobile_wallet_name' => 'Upay',
            ],
        ];
   
        // Create Mobile Wallet Name
        for ($i = 0; $i < count($mobileWallet); $i++) {
            MobileWallet::create([
                'user_id' => 1,
                'mobile_wallet_name' => $mobileWallet[$i]['mobile_wallet_name']
            ]);
        }
    }
}
