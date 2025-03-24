<?php

namespace App\Console\Commands;

use App\Models\Antrian;
use App\Models\Payment;
use App\Models\Installment;
use App\Models\AdditionalCost;
use Illuminate\Console\Command;
use App\Models\PaymentTransaction;

class MigratePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-payment {--limit=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Migrating payment transactions...');

        $payments = Payment::orderBy('created_at', 'desc')->take(50)->get();

        foreach ($payments as $payment) {
            $antrian = Antrian::where('ticket_order', $payment->ticket_order)->first();
            if (!$antrian) {
                $this->error('Antrian not found for ticket order: ' . $payment->ticket_order);
                return;
            }

            $status = $payment->payment_status;

            if ($status == 'Lunas') {
                $status = 'paid';
            } elseif ($status == 'Belum Bayar') {
                $status = 'unpaid';
            } else {
                $status = 'partially_paid';
            }

            try {
                $payTrans = new PaymentTransaction;
                $payTrans->antrian_id = $antrian->id;
                $payTrans->payment_status = $status;
                $payTrans->total_amount = $payment->total_payment;
                $payTrans->save();

                $installment = new Installment;
                $installment->payment_transaction_id = $payTrans->id;
                $installment->amount = $payment->payment_amount;
                $installment->payment_method = $payment->payment_method;
                $installment->status = $status;
                $installment->proof_file = $payment->payment_proof;
                $installment->validated_by = null;
                $installment->save();

                if($payment->installation_cost > 0) {
                    $additionalCost = new AdditionalCost;
                    $additionalCost->payment_transaction_id = $payTrans->id;
                    $additionalCost->type = 'installation';
                    $additionalCost->amount = $payment->installation_cost;
                    $additionalCost->save();
                }

                if($payment->shipping_cost > 0) {
                    $additionalCost = new AdditionalCost;
                    $additionalCost->payment_transaction_id = $payTrans->id;
                    $additionalCost->type = 'shipping';
                    $additionalCost->amount = $payment->shipping_cost;
                    $additionalCost->save();
                }

                if($antrian->packing_cost > 0) {
                    $additionalCost = new AdditionalCost;
                    $additionalCost->payment_transaction_id = $payTrans->id;
                    $additionalCost->type = 'packing';
                    $additionalCost->amount = $antrian->packing_cost;
                    $additionalCost->save();
                }

                $this->info('Payment transaction created for ticket order: ' . $payment->ticket_order);
            } catch (\Exception $e) {
                $this->error('Error migrating payment transactions: ' . $e->getMessage());
            }

        }
    }
}
