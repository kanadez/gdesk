<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Console\Command;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->arguments()['email'];
        $password = $this->arguments()['password'];
        if(User::where('email', $email)->exists()) {
            echo "User ".$email." already exists";
            return 0;
        }

        //$userService = new UserService();
        //$user = $userService->createUser($email, $email, $password);
        //$user->markEmailAsVerified();

        return 0;
    }
}
