<?php

namespace App\Console\Commands;

use App\Models\Kota;
use App\Models\Customer;
use App\Models\Provinsi;
use App\Models\CustomerTest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateKotaIdCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-kota-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update kota_id in pelanggan table based on alamat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $kotaList = Kota::all();
        $kotaList = Kota::all();
        $provinsiList = Provinsi::with('kota')->get();

        Customer::whereNull('kota_id')->chunk(100, function ($pelangganList) use ($kotaList) {
            foreach ($pelangganList as $pelanggan) {
                // Cek untuk kota
                foreach ($kotaList as $kota) {
                    // Hapus awalan "KABUPATEN" atau "KOTA" dari nama kota
                    $namaKota = preg_replace('/\b(KABUPATEN|KOTA)\s+/i', '', $kota->name);
                    if (stripos($pelanggan->alamat, $namaKota) !== false) {
                        $pelanggan->kota_id = $kota->id;
                        $pelanggan->save();
                        break;
                    }
                    $this->info('Updated Pelanggan ID: ' . $pelanggan->id);
                }
            }
        });

        //customer alamatnya pemalang, maka kota_id = 3327
        // Customer::where('alamat', 'like', '%SBY%')->update(['kota_id' => 3578, 'provinsi_id' => 35]);

        // Customer::whereNull('kota_id')->chunk(100, function ($pelangganList) use ($kotaList) {
        //     foreach ($pelangganList as $pelanggan) {
        //         foreach ($kotaList as $kota) {
        //             $namaKota = preg_replace('/\b(KABUPATEN|KOTA)\s+/i', '', $kota->name);
        //             $patternKota = "/\b" . preg_quote($namaKota, '/') . "\b/i";

        //             if (stripos($patternKota, $pelanggan->alamat) !== false) {
        //                 $pelanggan->kota_id = $kota->id;
        //                 $pelanggan->save();
        //                 break;
        //             }
        //         }
        //     }
        // });

        //hapus kota_id dengan value 1101
        //CustomerTest::where('kota_id', 1101)->update(['kota_id' => null]);

        // $cs = Customer::whereNotNull('kota_id')->whereNull('provinsi_id')->get();
        // //setel provinsi_id berdasarkan kota_id, misal kota_id = 1101, maka provinsi_id = 11
        // foreach ($cs as $c) {
        //     $kota = Kota::find($c->kota_id);
        //     $provinsiId = substr($kota->id, 0, 2);
        //     $c->provinsi_id = $provinsiId;
        //     $this->info('Updated Customer ID: ' . $c->id);
        //     $c->save();
        // }

        // $pelangganList = Customer::whereNull('kota_id')->get();

        // foreach ($pelangganList as $pelanggan) {
        //     foreach ($kotaList as $kota) {
        //         $namaKota = preg_replace('/\b(KABUPATEN|KOTA)\s+/i', '', $kota->name);
        //         if (stripos($pelanggan->alamat, $namaKota) !== false) {
        //             $pelanggan->kota_id = $kota->id;
        //             $pelanggan->save();
        //             $this->info('Updated Pelanggan ID: ' . $pelanggan->id);
        //             break;
        //         }
        //     }
        //

        // Customer::whereNull('kota_id')->whereNotNull('provinsi_id')->chunk(100, function ($pelangganList) use ($provinsiList) {
        //     foreach ($pelangganList as $pelanggan) {
        //         $listKota = Kota::where('provinsi_id', $pelanggan->provinsi_id)->get();
        //         foreach($listKota as $kota){
        //             $namaKota = preg_replace('/\b(KABUPATEN|KOTA)\s+/i', '', $kota->name);
        //             if (stripos($pelanggan->alamat, $namaKota) !== false) {
        //                 $pelanggan->kota_id = $kota->id;
        //                 $pelanggan->save();
        //                 $this->info('Updated Pelanggan ID: ' . $pelanggan->id);
        //                 break;
        //             }
        //         }
        //     }
        // });

        //jika kota_id tidak null, maka ambil 2 digit pertama dari kota_id sebagai provinsi_id
        // CustomerTest::whereNotNull('kota_id')->update([
        //     'provinsi_id' => DB::raw('LEFT(kota_id, 2)'),
        // ]);

        //jika kota_id , cari kota_id berdasarkan alamat
        // $kotaList = Kota::all();

        // Customer::chunk(100, function ($pelangganList) use ($kotaList) {
        //     foreach ($pelangganList as $pelanggan) {
        //         foreach ($kotaList as $kota) {
        //             $namaKota = preg_replace('/\b(KABUPATEN|KOTA)\s+/i', '', $kota->name);
        //             $patternKota = "/\b" . preg_quote($namaKota, '/') . "\b/i"; // Menggunakan batasan kata (\b)

        //             if (preg_match($patternKota, $pelanggan->alamat)) {
        //                 $provinsiId = $pelanggan->provinsi_id;
        //                 $kotaId = $kota->id;
        //                 $kotaIdPrefix = substr($kotaId, 0, 2);

        //                 if ($provinsiId != $kotaIdPrefix) {
        //                     $pelanggan->kota_id = $kota->id;
        //                     $pelanggan->save();
        //                     $this->info('Updated Pelanggan ID: ' . $pelanggan->id);
        //                 }
        //                 break;
        //             }
        //         }

        //         $this->info('Updated Pelanggan ID: ' . $pelanggan->id);
        //     }
        // });
    }
}
