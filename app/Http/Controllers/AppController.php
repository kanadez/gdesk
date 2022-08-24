<?php

namespace App\Http\Controllers;

use App\Models\Payments\Payout;
use App\Models\User;
use App\Services\ExchangeService;
use App\Services\Payment\Binance;
use App\Services\Payment\Coinbase;
use App\Services\Payment\Enot;
use App\Services\Payment\Interkassa;
use App\Services\Payment\Stripe;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AppController extends Controller
{
    public function index() {
        return view('gdesk');
    }

    public function test(Request $request) {
    }
}
