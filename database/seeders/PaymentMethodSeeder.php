<?php

namespace Database\Seeders;

use App\Models\Payments\PaymentMethod;
use App\Models\Payments\Paysystem;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $enot = Paysystem::where('slug','enot')->first();
        $lava = Paysystem::where('slug','lava')->first();
        $interkassa = Paysystem::where('slug', 'interkassa')->first();
        $primepayments = Paysystem::where('slug', 'primepayments')->first();
        $manual = Paysystem::where('slug', 'manual')->first();

        $paymentMethods = [
            [
                'paysystem_id' => $enot->id,
                'currency' => 'rub',
                'title' => 'Карта',
                'icon' => 'bank_card.svg',
                'pgroup' => 'card',
                'alias' => 'cd',
                'is_active' => true
            ],
            [
                'paysystem_id' => $enot->id,
                'currency' => 'rub',
                'title' => 'Qiwi',
                'icon' => 'qiwi.svg',
                'pgroup' => 'qiwi',
                'alias' => 'qw',
                'is_active' => true
            ],
            [
                'paysystem_id' => $lava->id,
                'currency' => 'rub',
                'title' => 'Карта',
                'icon' => 'bank_card.svg',
                'pgroup' => 'card',
                'alias' => 'cd',
                'is_active' => true
            ],
            [
                'paysystem_id' => $lava->id,
                'currency' => 'rub',
                'title' => 'Qiwi',
                'icon' => 'qiwi.svg',
                'pgroup' => 'qiwi',
                'alias' => 'qw',
                'is_active' => true
            ],
            [
                'paysystem_id' => $primepayments->id,
                'currency' => 'rub',
                'title' => 'Qiwi',
                'icon' => 'qiwi.svg',
                'pgroup' => 'qiwi',
                'alias' => '5',
                'is_active' => true,
            ],
            [
                'paysystem_id' => $primepayments->id,
                'currency' => 'rub',
                'title' => 'Карта',
                'icon' => 'bank_card.svg',
                'pgroup' => 'card',
                'alias' => '1',
                'is_active' => true,
            ],
            [
                'paysystem_id' => $primepayments->id,
                'currency' => 'rub',
                'title' => 'USDT (TRC-20)',
                'icon' => 'usdt.svg',
                'pgroup' => 'usdt',
                'alias' => '4',
                'is_active' => true,
            ],


            [
                'paysystem_id' => $interkassa->id,
                'currency' => 'rub',
                'title' => 'Qiwi',
                'icon' => 'qiwi.svg',
                'pgroup' => 'qiwi',
                'alias' => 'qiwi',
                'is_active' => false,
            ],
            [
                'paysystem_id' => $interkassa->id,
                'currency' => 'rub',
                'title' => 'Карта',
                'icon' => 'bank_card.svg',
                'pgroup' => 'card',
                'alias' => 'card',
                'is_active' => false,
            ],
            [
                'paysystem_id' => $manual->id,
                'currency' => 'rub',
                'title' => 'Coinbase',
                'icon' => '',
                'pgroup' => 'coinbase',
                'alias' => 'coinbase',
                'is_active' => true,
            ]

        ];

        foreach($paymentMethods as $paymentMethod) {
            PaymentMethod::create($paymentMethod);
        }
    }
}
