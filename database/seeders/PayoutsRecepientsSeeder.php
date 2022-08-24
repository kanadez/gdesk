<?php

namespace Database\Seeders;
use App\Models\Contact;
use App\Models\Payments\Payout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayoutsRecepientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->seedByTelegram();
        $this->seedByCard();
    }

    private function seedByTelegram()
    {
        $telegrams = Contact::select('user_id', 'contact')->where('type', 'telegram')->get();
        $payouts = Payout::all();

        foreach ($payouts as $payout) {
            foreach ($telegrams as $telegram) {
                if ($payout->comment == $telegram->contact) {
                    $payout->recipient_id = $telegram->user_id;
                    $payout->save();
                }
            }

        }
    }

    private function seedByCard()
    {
        $users = User::select()->with('contacts')->get();
        $payouts = Payout::select()->whereNull('recipient_id')->get();

        foreach ($payouts as $payout) {
            foreach ($users as $user) {
                if ($user->card == $payout->address) {
                    $payout->recipient_id = $user->id;
                    $payout->save();
                }
            }
        }
    }
}
