<?php

namespace App\Exports;

use App\Models\Antrian;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HasilIklanExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Antrian::with('customer', 'sales', 'job', 'customer.kota')
        ->whereHas('customer', function($query) {
            $query->where('infoPelanggan', 'like', '%Iklan%')
            ->where('frekuensi_order', 1);
        })
        ->whereBetween('created_at', ['2024-12-01', '2024-12-31'])
        ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Dibuat Pada',
            'Ticket Order',
            'Sales',
            'Nama Produk',
            'Kota',
            'Sumber Pelanggan',
            'Omset',
        ];
    }

    public function map($workshop): array
    {
        return [
            $workshop->created_at,
            $workshop->ticket_order,
            $workshop->sales->sales_name,
            $workshop->job->job_name,
            $workshop->customer->kota->name ?? '-',
            $workshop->customer->infoPelanggan,
            $workshop->omset,
        ];
    }
}
