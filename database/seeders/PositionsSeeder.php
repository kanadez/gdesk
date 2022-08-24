<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = [
            "Sales/Support",
            "Development",
            "DevOps",
            "Marketing",
            "HR",
            "BackOffice",
        ];

        foreach ($positions as $position) {
            DB::table('positions')->insert([
                'title' => $position
            ]);
        }
    }
}
