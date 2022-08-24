<?php

namespace Database\Seeders;

use App\Models\Payments\Paysystem;
use Illuminate\Database\Seeder;

class PaysystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paysystems = [
            [
                'title' => 'Enot',
                'slug' => 'enot',
            ],
            [
                'title' => 'Lava',
                'slug' => 'lava',
            ],
            [
                'title' => 'PrimePayments',
                'slug' => 'primepayments'
            ],
            [
                'title' => 'Interkassa',
                'slug' => 'interkassa',
                'is_active' => false,
            ],
            [
                'title' => 'Вручную',
                'slug' => 'manual',
                'show_balance' => false,
            ]
        ];

        foreach($paysystems as $paysystem) {
            if(!Paysystem::where('slug', $paysystem['slug'])->exists()) {
                Paysystem::create($paysystem);
            }
        }
    }
}
