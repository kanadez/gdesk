<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            //UserSeeder::class,
            //PaysystemSeeder::class,
            //CategoriesSeeder::class,
            //PaymentMethodSeeder::class,
            //PayoutsSeeder::class,
            //PositionsSeeder::class,
            //RolesPermissionsSeeder::class, // не выполнен
            //PayoutsRecepientsSeeder::class
            LocationsTagsSeeder::class
        ]);
    }
}
