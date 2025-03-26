<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LockedLaporanHarian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:locked-laporan-harian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengunci antrian laporan harian yang belum dikunci';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reports = DailyReport::where('is_locked', false)->get();

        foreach ($reports as $report) {
            $report->is_locked = true;
            $report->save();
        }

        $this->info('All unlocked reports have been successfully locked.');
    }
}
