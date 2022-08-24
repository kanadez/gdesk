<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Payments\PaymentMethod;
use App\Models\Payments\Payout;
use App\Models\Payments\Paysystem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PayoutsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<50;$i++) {
            $paysystem = Paysystem::where('is_active', true)->inRandomOrder()->first();
            $method = PaymentMethod::where('paysystem_id', $paysystem->id)->inRandomOrder()->first();
            if(!isset($method->id)) {
                dd($paysystem);
            }
            $category = Category::inRandomOrder()->first();
            $user = User::inRandomOrder()->first();
            $address = Str::random(16);
            Payout::create([
                'payment_method_id' => $method->id,
                'user_id' => $user->id,
                'address' => $address,
                'category_id' => rand(0,10) > 5 ? $category->id : null,
                'amount' => rand(50, 1000),
                'status' => 'created',
            ]);
        }
    }
}
