<?php

namespace App\Console\Commands;

use App\Models\Payments\Payout;
use App\Services\PaymentService;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PayoutStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payouts:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates payouts statuses';

    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payoutsForUpdate = DB::table("payouts as p")->join("payment_methods as pm", "p.payment_method_id", "=", "pm.id")->join("paysystems as ps", "pm.paysystem_id", "=", "ps.id")
            ->select(["p.*", "ps.slug", "pm.alias"])->whereIn("p.status", [Payout::STATUS_SENT, Payout::STATUS_UNKNOWN, Payout::STATUS_PENDING])->whereNotNull("external_id")->get();

        foreach($payoutsForUpdate as $payout) {
            $this->paymentService->updateStatus($payout);
        }
        return 0;
    }
}
