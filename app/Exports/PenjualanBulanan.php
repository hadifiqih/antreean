<?php

namespace App\Exports;

use App\Models\Antrian;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PenjualanBulanan implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        $awal = '2024-01-01 00:00:00';
        $akhir = '2025-03-02 23:59:59';
        return Antrian::query()
        ->with(['order', 'customer', 'job', 'platform'])
        ->whereHas('job', function($query){
            $query->where('job_type', 'like', '%Advertising%')->orWhere('job_type', 'like', '%Non%');
        })
        ->whereBetween('created_at', [$awal, $akhir]);
    }

    public function headings(): array
    {
        return [
            'Tiket Order',
            'Tanggal Order',
            'Sales',
            'Kota',
            'Nama Produk',
            'Platform',
            'Spesifikasi',
            'Omset',
        ];
    }

    public function map($antrian): array
    {
        return [
            "'" . $antrian->ticket_order,
            $antrian->created_at->format('d-m-Y'),
            $antrian->sales->sales_name,
            $antrian->customer->kota->name ?? '-',
            $antrian->job->job_name,
            $antrian->platform->platform_name ?? '-',
            $antrian->note,
            $antrian->harga_produk * $antrian->qty ?? 0,
        ];
    }
}
