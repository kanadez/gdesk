<?php

namespace App\Services;

use App\Exceptions\PayoutSendException;
use App\Exports\PayoutsExport;
use App\Filters\Payments\PayoutsFilter;
use App\Models\Category;
use App\Models\Payments\PaymentMethod;
use App\Models\Payments\Payout;
use App\Models\Payments\Paysystem;
use App\Models\User;
use App\Services\Payment\BalanceResource;
use App\Services\Payment\Binance;
use App\Services\Payment\Coinbase;
use App\Services\Payment\Enot;
use App\Services\Payment\Interkassa;
use App\Services\Payment\Lava;
use App\Services\Payment\Manual;
use App\Services\Payment\PayoutResponse;
use App\Services\Payment\Paypalych;
use App\Services\Payment\PaysystemResponse;
use App\Services\Payment\PrimePayments;
use App\Services\Payment\Qiwi;
use App\Services\Payment\Stripe;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Stripe\Balance;

class UserPayoutService
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getPayouts(Request $request, $download = false) {
        $userSettings = $this->userService->getSettings(auth()->user());

        if (!isset($userSettings['per_page']) || $request->input('per_page', 25) !== $userSettings['per_page']) {
            $userSettings['per_page'] = $request->input('per_page', 25);
            $this->userService->saveSettings(auth()->user(), 'per_page', $request->input('per_page', 25));
        }

        $payouts = DB::table('payouts as p')
                        ->join('payment_methods as pm', 'p.payment_method_id','=','pm.id')
                        ->join('paysystems as ps', 'pm.paysystem_id','=','ps.id')
                        ->leftJoin("categories as c", "p.category_id", "=", "c.id")
                        ->where('recipient_id', auth()->user()->id)
                        ->select(['p.*','pm.title as payment_method_title', 'ps.title as paysystem_title','pm.paysystem_id', 'c.title as category_title'])
                        ->orderBy('id','desc');
        $payouts = PayoutsFilter::filter($payouts, $request);
        $total = $payouts->sum('amount');

        if (!$download) {
            $payouts = $payouts->paginate($userSettings['per_page']);
        } else {
            $payouts = $payouts->get();
        }

        return ['total' => $total, 'payouts' => $payouts];
    }

    public function downloadPayouts(Request $request) {
        $payouts = $this->getPayouts($request, true);
        return Excel::download(new PayoutsExport($payouts["payouts"]), 'payouts-'.Carbon::now()->format("Y-m-d").'.xlsx');
    }

}
