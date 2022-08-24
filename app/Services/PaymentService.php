<?php

namespace App\Services;

use App\Exceptions\PayoutSendException;
use App\Exports\PayoutsExport;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Stripe\Balance;
use App\Filters\Payments\PayoutsFilter;

class PaymentService
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getPaymentMethods() {
        $time = microtime(true);
        $paysystems = Paysystem::with('paymentMethods')->where("is_active", true)->whereRelation("paymentMethods",'is_active', true)->orderBy('sort_num', 'asc')->get();
        foreach($paysystems as &$paysystem) {
            $paysystem->rates = $this->getRatesFor($paysystem);
        }

        return $paysystems;
    }

    public function getRatesFor($paysystem) {
        $provider = $this->resolveProvider($paysystem->slug);

        switch($paysystem->slug) {
            case "coinbase" :

                $coinbase = new Coinbase();
                try {
                    $response = $coinbase->request("GET", "exchange-rates?currency=RUB");
                    return json_decode($response->body())->data->rates;
                } catch (\Exception$exception) {

                }
                ; break;
            case "binance" :
                return $provider->getRates();
                break;
            case "interkassa" :
                $rates = $provider->getRates();
                foreach($rates as $currency => $rate) {
                    $rates[$currency] = 1/$rate;
                }
                return $rates;
            default : return null;
        }
    }

    public function getCategories() {
        return Category::orderBy('title','asc')->get();
    }

    public function getPayouts(Request $request, $download = false) {
        $userSettings = $this->userService->getSettings(auth()->user());

        if(!isset($userSettings['per_page']) || $request->input('per_page', 25) !== $userSettings['per_page']) {
            $userSettings['per_page'] = $request->input('per_page', 25);
            $this->userService->saveSettings(auth()->user(), 'per_page', $request->input('per_page', 25));
        }

        $payouts = DB::table('payouts as p')
                        ->join('payment_methods as pm', 'p.payment_method_id','=','pm.id')
                        ->join('paysystems as ps', 'pm.paysystem_id','=','ps.id')
                        ->leftJoin("categories as c", "p.category_id", "=", "c.id")
                        ->select(['p.*','pm.title as payment_method_title', 'ps.title as paysystem_title','pm.paysystem_id', 'c.title as category_title'])
                        ->orderBy('id','desc');
        $payouts = PayoutsFilter::filter($payouts, $request);
        $total = $payouts->sum('amount');

        if(!$download) {
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

    public function getPayoutsUsers() {
        $users = User::select()
            ->with('contacts')
            ->whereRaw('card IS NOT NULL AND card != ""')
            ->whereNull('deleted_at')
            ->orderBy('name','asc')
            ->get();

        return $users->map(function ($user) { // TODO вынести в презентер
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'payout_method' => $user->payout_method,
                'payout_to' => $user->payout_method == 'card' ? $user->card : $user->crypto_address, // TODO хорошо бы тоже отедьно вынести такое, getPayoutsUsers не должна занть об этом ничего
                'telegram' => $user->telegram(),
                'name_with_telegram' => $user->name . ($user->telegram() != null ? " ({$user->telegram()})" : ''),
                'name_transliterated' => Str::transliterate($user->name),
            ];
        });
    }

    public function send($request) {
        $method = PaymentMethod::with('paysystem')->where('id', $request->input('payment_method_id'))->first();
        $payout = Payout::create([
            'payment_method_id' => $method->id,
            'category_id' => $request->input('category_id'),
            'user_id' => auth()->id(),
            'recipient_id' => $request->input('recipient_id') ?? null,
            'address' => $request->input('address'),
            'amount' => $request->input('amount'),
            'status' => Payout::STATUS_CREATED,
            'is_internal' => $request->input('is_internal', false),
            'comment' => is_null($request->input('comment')) ? '' : $request->input('comment'),
        ]);

        $provider = $this->resolveProvider($method->paysystem->slug);

        if(env('APP_ENV') !== 'production') {
            $payout->update([
                'status' => Payout::STATUS_UNKNOWN,
                'paysystem_status' => 'local',
                'payload' => json_encode(["message" => "Payout was not sent due to local development environment"])
            ]);
            return;
        }

        $options = [];
        if(is_array($method->options) && array_key_exists('required', $method->options)) {
            foreach($method->options["required"] as $field) {
                if($request->has($field['var'])) {
                    $options[$field['var']] = $request->input($field['var']);
                }
            }
        }

        $response = $provider->send($payout->address, $payout->amount, $payout->id, $method->alias, $options);

        $payout->update([
            'external_id' => $response->externalId,
            'status' => $response->payoutStatus,
            'paysystem_status' => $response->payoutExternalStatus,
            'payload' => $response->payload
        ]);

    }

    protected function resolveProvider(string $slug) {
        switch($slug) {
            case 'enot' : return new Enot(
                config('services.paysystems.enot.base_uri'),
                config('services.paysystems.enot.api_key'),
                config('services.paysystems.enot.email')
            );
            case 'enot_p2p' : return new Enot(
                config('services.paysystems.enot_p2p.base_uri'),
                config('services.paysystems.enot_p2p.api_key'),
                config('services.paysystems.enot_p2p.email')
            );
            case 'lava' : return new Lava();
            case 'primepayments' : return new PrimePayments(
                config("services.paysystems.primepayments.base_uri"),
                config("services.paysystems.primepayments.project_id"),
                config("services.paysystems.primepayments.secret1"),
                config("services.paysystems.primepayments.email")
            );
            case 'primepayments2' : return new PrimePayments(
                config("services.paysystems.primepayments2.base_uri"),
                config("services.paysystems.primepayments2.project_id"),
                config("services.paysystems.primepayments2.secret1"),
                config("services.paysystems.primepayments2.email")
            );
            case 'interkassa' : return new Interkassa();
            case 'qiwi' : return new Qiwi();
            case 'coinbase' : return new Coinbase();
            case 'stripe' : return new Stripe();
            case 'binance' : return new Binance(
                config("services.paysystems.binance.base_uri"),
                config("services.paysystems.binance.api_key"),
                config("services.paysystems.binance.api_secret")
            );
            case "paypalych" : return new Paypalych();
            case 'manual' : return new Manual();
        }
    }

    public function updateStatus($payout) {
        $provider = $this->resolveProvider($payout->slug);
        $response = $provider->updateStatus($payout);
        DB::table("payouts")->where("id", $payout->id)->update([
            'status' => $response->payoutStatus,
            'paysystem_status' => $response->payoutExternalStatus,
            'payload' => $response->payload
        ]);
    }

    public function getBalance() {
        $paysystems = Paysystem::where('show_balance', true)->where('is_active', true)->orderBy('sort_num','asc')->get();

        $balances = [];
        foreach($paysystems as $paysystem) {
            $balance = new BalanceResource();
            $balance->setFromJson($paysystem->balance);
            $balances[] = [
                'provider' => $paysystem->title,
                'balance' => $balance
            ];
        }
        return $balances;
    }

    public function updateBalance() {
        $paysystems = Paysystem::where('show_balance', true)->where('is_active', true)->orderBy('sort_num','asc')->get();
        foreach($paysystems as $paysystem) {
            $provider = $this->resolveProvider($paysystem->slug);
            $balance = $provider->balance();
            Paysystem::where('slug', $paysystem->slug)->update([
                'balance' => json_encode($balance)
            ]);
        }
    }

    public function deletePayout($id) {
        Payout::where('id', $id)->where('status', Payout::STATUS_FAILED)->delete();
    }

    public function editCategory(Request $request) {

        Category::updateOrCreate([
            'id' => $request->input('id')
        ], [
            'title' => $request->input('title')
        ]);
        return $this->getCategories();
    }

    public function deleteCategory(Request $request) {
        Payout::where('category_id', $request->input('id'))->update(['category_id' => null]);
        Category::where('id', $request->input('id'))->delete();
        return $this->getCategories();
    }

    public function editPaymentMethod(Request $request) {
        PaymentMethod::updateOrCreate(['id' => $request->input('id')], [
            'paysystem_id' => $request->input('paysystem_id'),
            'title' => $request->input('title'),
            'currency' => $request->input('currency'),
            'icon' => $request->input('icon'),
            'pgroup' => $request->input('pgroup'),
            'alias' => $request->input('alias'),
            'min' => $request->input('min'),
            'max' => $request->input('max'),
            'is_active' => $request->input('is_active', false),
        ]);
        return $this->getPaymentMethods();
    }

    public function deletePaymentMethod(Request $request) {
        PaymentMethod::where('id', $request->input('id'))->delete();
        return $this->getPaymentMethods();
    }

}
