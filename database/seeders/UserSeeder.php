<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(UserService $userService)
    {
        if(User::where('email', env('ADMIN_LOGIN'))->exists()) {
            return;
        }
        $user = $userService->createUser(env('ADMIN_LOGIN'), env('ADMIN_LOGIN'), env('ADMIN_PASSWORD'));
        $userService->verify($user);
    }
}
