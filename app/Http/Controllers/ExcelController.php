<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use App\Exports\WorkshopExport;
use App\Exports\HasilIklanExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new WorkshopExport, 'workshop.xlsx');
    }

    public function exportExcelCustomer()
    {
        return Excel::download(new CustomerExport, 'customer.xlsx');
    }

    public function hasilIklan()
    {
        return Excel::download(new HasilIklanExport, 'hasil_iklan.xlsx');
    }
}
