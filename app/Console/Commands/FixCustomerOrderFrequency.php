<?php

namespace App\Console\Commands;

use App\Models\Antrian;
use App\Models\Customer;
use Illuminate\Console\Command;

class FixCustomerOrderFrequency extends Command
{
    protected $signature = 'app:fix-customer-order-frequency';

    protected $description = 'Memperbaiki frekuensi order pelanggan berdasarkan data antrian yang ada';

    public function handle()
    {
        $this->info('Mulai memperbaiki frekuensi order pelanggan...');

        Customer::chunk(100, function ($customers) {
            foreach ($customers as $customer) {
                $this->fixCustomerOrderFrequency($customer);
            }
        });

        $this->info('Proses perbaikan frekuensi order pelanggan selesai.');
    }

    private function fixCustomerOrderFrequency(Customer $customer)
    {
        $newFrequency = Antrian::where('customer_id', $customer->id)
            ->selectRaw('DATE(created_at) as order_date')
            ->groupBy('order_date')
            ->get()
            ->count();

        if ($customer->frekuensi_order !== $newFrequency) {
            $oldFrequency = $customer->frekuensi_order;
            $customer->frekuensi_order = $newFrequency;
            $customer->save();

            $this->line("Pelanggan ID {$customer->id}: frekuensi order diperbarui dari {$oldFrequency} menjadi {$newFrequency}");
        } else {
            $this->line("Pelanggan ID {$customer->id}: frekuensi order tetap {$newFrequency}");
        }
    }
}
