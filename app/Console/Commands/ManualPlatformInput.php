<?php

namespace App\Console\Commands;

use App\Models\AntrianIklan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ManualPlatformInput extends Command
{
    protected $signature = 'app:manual-platform-input';

    protected $description = 'Command description';

    public function handle()
    {
        $antrianIklans = AntrianIklan::where('platform_id', 0)->get();

        foreach ($antrianIklans as $antrianIklan) {
            $platform = DB::table('platforms')
                ->where('platform_name', 'like', '%' . $antrianIklan->barang->customer->infoPelanggan . '%')
                ->first();

            if ($platform) {
                $antrianIklan->platform_id = $platform->id;
                $antrianIklan->save();
            }
        }
    }
}
