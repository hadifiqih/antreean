<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Antrian;


class PenjualanSalesTertentu implements FromCollection, WithHeadings, WithMapping
{
    protected $salesId;
    protected $startDate;
    protected $endDate;

    public function __construct($salesId, $startDate, $endDate)
    {
        $this->salesId = $salesId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Antrian::with(['customer', 'sales', 'job', 'payment'])
            ->where('sales_id', $this->salesId)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Sales',
            'Customer',
            'Sumber Pelanggan',
            'Jenis Produk',
            'Harga Produk',
            'Qty',
            'Omset'
        ];
    }

    public function map($antrian): array
    {
        return [
            $antrian->created_at->format('d/m/Y'),
            $antrian->sales->sales_name,
            $antrian->customer->nama,
            $antrian->customer->infoPelanggan,
            $antrian->job->job_name,
            $antrian->harga_produk,
            $antrian->qty,
            number_format($antrian->omset, 0, ',', '.'),
        ];
    }
}
