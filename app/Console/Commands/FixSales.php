<?php

namespace App\Console\Commands;

use App\Models\Antrian;
use App\Models\Customer;
use Illuminate\Console\Command;

class FixSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-sales';

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
        $customers = Customer::whereNull('sales_id')->get();

        foreach ($customers as $customer) {
            // Get the latest antrian record for this customer
            $latestAntrian = Antrian::where('customer_id', $customer->id)
                ->whereNotNull('sales_id')
                ->latest()
                ->first();

            if ($latestAntrian) {
                $customer->sales_id = $latestAntrian->sales_id;
                $customer->save();
                $this->info("Updated sales_id for customer ID: {$customer->id}");
            }
        }

        $this->info('Sales ID update completed!');
    }
}
