<?php

namespace App\Http\Controllers;

use PDF;
use Dompdf\Dompdf;

use App\Models\Job;
use App\Models\Sales;
use App\Models\Antrian;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Exports\PenjualanBulanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ReportResource;
use App\Exports\PenjualanSalesTertentu;



class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $tanggalAwal adalah selalu tanggal 1 dari bulan yang dipilih
        $tanggalAwal = date('Y-m-01 00:00:00');
        // $tanggalAkhir adalah selalu tanggal sekarang dari bulan yang dipilih
        $tanggalAkhir = date('Y-m-d 23:59:59');

        $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $totalOmset = 0;
        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
        }

        return new ReportResource(true, 'Data omset global sales berhasil diambil', $antrians, $totalOmset);
    }

    public function pilihTanggal()
    {
        return view('page.antrian-workshop.pilih-tanggal');
    }

    public function pilihTanggalDesain()
    {
        return view('page.antrian-desain.pilih-tanggal');
    }

    public function showExportFormSalesTertentu()
    {
        $sales = Sales::all();
        return view('page.report.export-sales-tertentu', compact('sales'));
    }

    public function exportPenjualanSalesTertentu(Request $request)
    {
        $salesId = $request->sales_id;
        $startDate = $request->start_date . ' 00:00:00';
        $endDate = $request->end_date . ' 23:59:59';

        return Excel::download(
            new PenjualanSalesTertentu($salesId, $startDate, $endDate),
            'penjualan-sales-'.$request->sales_id.'.xlsx'
        );
    }

    public function exportLaporanDesainPDF(Request $request)
    {

        $tanggal = $request->tanggal;
        //Mengambil data antrian dengan relasi customer, sales, payment, operator, finishing, job, order pada tanggal yang dipilih dan menghitung total omset dan total order
        $antrians = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereDate('created_at', $tanggal)
            ->get();

        $totalOmset = 0;
        $totalQty = 0;
        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
            $totalQty += $antrian->qty_produk;
        }

        $pdf = PDF::loadview('page.antrian-workshop.laporan-desain', compact('antrians', 'totalOmset', 'totalQty', 'tanggal'));
        return $pdf->stream($tanggal . '-laporan-desain.pdf');
        // return $pdf->download($tanggal . '-laporan-workshop.pdf');
    }

    public function exportLaporanWorkshopPDF(Request $request)
    {
        $tempat = $request->tempat_workshop;
        // $tanggalAwal adalah selalu tanggal 1 dari bulan yang dipilih
        $tanggalAwal = date('Y-m-01 00:00:00');
        // $tanggalAkhir adalah selalu tanggal sekarang dari bulan yang dipilih
        $tanggalAkhir = date('Y-m-d 23:59:59');

        //Mengambil data antrian dengan relasi customer, sales, payment, operator, finishing, job, order pada tanggal yang dipilih dan menghitung total omset dan total order
        $antrianStempel = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where(function ($query) use ($tempat) {
                $query->whereHas('sales', function ($subquery) use ($tempat) {
                    $subquery->where('sales_name', 'like', '%' . $tempat . '%');
                })
                ->whereHas('job', function ($subquery) {
                    $subquery->where('job_type', 'Stempel');
                });
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            })
            ->get();

        $antrianAdvertising = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where(function ($query) use ($tempat) {
                $query->whereHas('sales', function ($subquery) use ($tempat) {
                    $subquery->where('sales_name', 'like', '%' . $tempat . '%');
                })
                ->whereHas('job', function ($subquery) {
                    $subquery->where('job_type', 'Advertising');
                });
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            })
            ->get();


        $antrianNonStempel = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where(function ($query) use ($tempat) {
                $query->whereHas('sales', function ($subquery) use ($tempat) {
                    $subquery->where('sales_name', 'like', '%' . $tempat . '%');
                })
                ->whereHas('job', function ($subquery) {
                    $subquery->where('job_type', 'Non Stempel');
                });
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            })
            ->get();

        $antrianDigiPrint = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where(function ($query) use ($tempat) {
                $query->whereHas('sales', function ($subquery) use ($tempat) {
                    $subquery->where('sales_name', 'like', '%' . $tempat . '%');
                })
                ->whereHas('job', function ($subquery) {
                    $subquery->where('job_type', 'Digital Printing');
                });
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            })
            ->get();

        //buat beberapa variabel dengan nilai 0 untuk menampung total omset dan total order
        $totalOmsetStempel = 0;
        $totalQtyStempel = 0;

        $totalOmsetAdvertising = 0;
        $totalQtyAdvertising = 0;

        $totalOmsetNonStempel = 0;
        $totalQtyNonStempel = 0;

        $totalOmsetDigiPrint = 0;
        $totalQtyDigiPrint = 0;

        //looping untuk menghitung total omset dan total order
        foreach ($antrianStempel as $antrian) {
            $totalOmsetStempel += $antrian->omset;
            $totalQtyStempel += $antrian->qty;
        }

        foreach ($antrianAdvertising as $antrian) {
            $totalOmsetAdvertising += $antrian->omset;
            $totalQtyAdvertising += $antrian->qty;
        }

        foreach ($antrianNonStempel as $antrian) {
            $totalOmsetNonStempel += $antrian->omset;
            $totalQtyNonStempel += $antrian->qty;
        }

        foreach ($antrianDigiPrint as $antrian) {
            $totalOmsetDigiPrint += $antrian->omset;
            $totalQtyDigiPrint += $antrian->qty;
        }

        $pdf = PDF::loadview('page.antrian-workshop.laporan-workshop', compact('tanggalAwal', 'tanggalAkhir', 'totalOmsetStempel', 'totalQtyStempel', 'totalOmsetAdvertising', 'totalQtyAdvertising', 'totalOmsetNonStempel', 'totalQtyNonStempel', 'totalOmsetDigiPrint', 'totalQtyDigiPrint', 'antrianStempel', 'antrianNonStempel', 'antrianAdvertising', 'antrianDigiPrint', 'tempat'))->setPaper('folio', 'landscape');
        return $pdf->stream($tempat .  '_Laporan_Workshop.pdf');
    }

    public function cetakEspk($id)
    {
        $antrian = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->where('ticket_order', $id)
            ->first();

        $pdf = PDF::loadview('page.antrian-workshop.cetak-spk-workshop', compact('antrian'))->setPaper('folio', 'landscape');
        return $pdf->stream("Adm_" . $antrian->ticket_order . "_" . $antrian->order->title . '_espk.pdf');

        // return view('page.antrian-workshop.cetak-spk-workshop', compact('antrian'));
    }

    public function reportSales()
    {
        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $salesId = $sales->id;

        $totalOmset = 0;

        $date = date('Y-m-d');

        $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->whereDate('created_at', $date)
            ->where('sales_id', $salesId)
            ->get();

        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
        }

        return view('page.antrian-workshop.ringkasan-sales', compact('antrians', 'totalOmset', 'date'));
    }

    public function reportSalesByDate()
    {
        if(request()->has('tanggal')) {
            $date = request('tanggal');
        } else {
            $date = date('Y-m-d');
        }

        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $salesId = $sales->id;

        $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->whereDate('created_at', $date)
            ->where('sales_id', $salesId)
            ->get();

        $totalOmset = 0;
        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
        }

        return view('page.antrian-workshop.ringkasan-sales', compact('antrians', 'totalOmset', 'date'));
    }

    public function reportFormOrder($id)
    {
     $antrian = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->where('ticket_order', $id)
            ->first();
     // return view('page.antrian-workshop.form-order', compact('antrian'));
        $pdf = PDF::loadview('page.antrian-workshop.form-order', compact('antrian'))->setPaper('a4', 'portrait');
        return $pdf->stream($antrian->ticket_order . "_" . $antrian->order->title . '_form-order.pdf');
    }

    public function omsetGlobalSales()
    {
        //melakukan perulangan tanggal pada bulan ini, menyimpannya dalam array
        $dateRange = [];
        $dateAwal = date('Y-m-01');
        $dateAkhir = date('Y-m-d');
        $date = $dateAwal;

        while (strtotime($date) <= strtotime($dateAkhir)) {
            $dateRange[] = $date;
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        return view('page.report.omset-global-sales', compact('dateRange'));
    }

    public function omsetPerCabang()
    {
        //melakukan perulangan tanggal pada bulan ini, menyimpannya dalam array
        $dateRange = [];
        $dateAwal = date('Y-m-01');
        $dateAkhir = date('Y-m-d');
        $date = $dateAwal;

        while (strtotime($date) <= strtotime($dateAkhir)) {
            $dateRange[] = $date;
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        return view('page.report.omset-per-cabang', compact('dateRange'));
    }

    public function omsetPerProduk()
    {
        //melakukan perulangan tanggal pada bulan ini, menyimpannya dalam array
        $dateRange = [];
        $dateAwal = date('Y-m-01');
        $dateAkhir = date('Y-m-d');
        $date = $dateAwal;

        while (strtotime($date) <= strtotime($dateAkhir)) {
            $dateRange[] = $date;
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        return view('page.report.omset-per-produk', compact('dateRange'));
    }

    public function exportPDFBaruWorkshop(Request $request)
    {
        $tempat = $request->tempat_workshop;
        $tanggalAkhir = date('Y-m-d 23:59:59');
        $tanggalAwal = date('Y-m-01 00:00:00');
        //Mengambil data antrian dengan relasi customer, sales, payment, operator, finishing, job, order pada tanggal yang dipilih dan menghitung total omset dan total order
        $workshops = Antrian::with(['customer','sales', 'job'])->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get();

        $totalOmset = 0;
        foreach ($workshops as $workshop) {
            $totalOmset += $workshop->omset;
        }

        return view('page.report.laporan-workshop', compact('tanggalAwal', 'tanggalAkhir', 'workshops', 'totalOmset'));
    }

    public function rankingSales()
    {
        $sales = Sales::all();

        $tanggalAkhir = date('Y-m-d 23:59:59');
        // $tanggalAwal = date('Y-m-d 00:00:00', strtotime('-1 week'));
        $tanggalAwal = date('Y-m-01 00:00:00');

        foreach($sales as $sale) {
            $workshops = Antrian::with(['customer','sales', 'job'])->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->where('sales_id', $sale->id)->get();
            $totalOmset = 0;
            foreach ($workshops as $workshop) {
                $totalOmset += $workshop->omset;
            }
            $sale->total_omset = $totalOmset;
        }

        $sales = $sales->sortByDesc('total_omset');

        $totalOmset = 0;
        foreach ($sales as $sale) {
            $totalOmset += $sale->total_omset;
        }

        return view('page.report.ranking-sales', compact('sales', 'tanggalAwal', 'tanggalAkhir', 'totalOmset'));
    }

    public function produkTerlaris()
    {
        $jobs = Job::all();

        $tanggalAkhir = date('Y-m-d 23:59:59');
        $tanggalAwal = date('Y-m-01 00:00:00');

        foreach($jobs as $job) {
            $workshops = Antrian::with(['customer','sales', 'job'])->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->where('job_id', $job->id)->get();
            $totalOmset = 0;
            foreach ($workshops as $workshop) {
                $totalOmset += $workshop->omset;
            }
            $job->total_omset = $totalOmset;
        }

        $jobs = $jobs->sortByDesc('total_omset');

        $totalOmset = 0;
        foreach ($jobs as $job) {
            $totalOmset += $job->total_omset;
        }

        return view('page.report.produk-terlaris', compact('jobs', 'tanggalAwal', 'tanggalAkhir', 'totalOmset'));
    }

    public function omsetBulananSemuaSales()
    {
        $sales = Sales::all();
        $tanggalAkhir = date('Y-m-d 23:59:59');
        $tanggalAwal = date('Y-m-01 00:00:00');
        $totalOmsetBulanan = 0;

        foreach($sales as $sale) {
            $antrians = Antrian::with(['customer','sales', 'job'])->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->where('sales_id', $sale->id)->get();
            $totalOmset = 0;
            foreach ($antrians as $antrian) {
                $totalOmset += $antrian->omset;
            }
            $sale->total_omset = $totalOmset;
            $totalOmsetBulanan += $totalOmset;
        }

        $sales = $sales->sortByDesc('total_omset');

        return view('page.report.omset-bulanan-semua-sales', compact('sales', 'tanggalAwal', 'tanggalAkhir', 'totalOmsetBulanan'));
    }

    public function penjualanBulananExport()
    {
        return Excel::download(new PenjualanBulanan, 'penjualan-bulanan.xlsx');
    }

    public function performance()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $employees = Employee::where('status', 1)->get();
        $monthlyStats = [];

        foreach ($employees as $employee) {
            // Get antrians where employee is involved in any role
            $antrians = Antrian::where(function($query) use ($employee, $startDate, $endDate) {
                $query->whereRaw("FIND_IN_SET(?, operator_id)", [$employee->id])
                      ->orWhereRaw("FIND_IN_SET(?, finisher_id)", [$employee->id])
                      ->orWhereRaw("FIND_IN_SET(?, qc_id)", [$employee->id]);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

            $operatorTasks = $antrians->filter(function($a) use ($employee) {
                $ids = explode(',', $a->operator_id);
                return in_array($employee->id, $ids); 
            });
            
            $finisherTasks = $antrians->filter(function($a) use ($employee) {
                $ids = explode(',', $a->finisher_id); 
                return in_array($employee->id, $ids);
            });
            
            $qcTasks = $antrians->filter(function($a) use ($employee) {
                $ids = explode(',', $a->qc_id);
                return in_array($employee->id, $ids);
            });
            
            // Tasks are completed if timer_stop is set and deadline_status is 1
            $completedOperator = $operatorTasks->filter(fn($a) => !is_null($a->timer_stop) && $a->deadline_status == 1)->count();
            $completedFinisher = $finisherTasks->filter(fn($a) => !is_null($a->timer_stop) && $a->deadline_status == 1)->count();
            $completedQc = $qcTasks->filter(fn($a) => !is_null($a->timer_stop) && $a->deadline_status == 1)->count();

            $totalOperator = $operatorTasks->count();
            $totalFinisher = $finisherTasks->count();
            $totalQc = $qcTasks->count();

            $totalTasks = $totalOperator + $totalFinisher + $totalQc;
            $completedTasks = $completedOperator + $completedFinisher + $completedQc;
            
            $completionRate = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

            $monthlyStats[] = [
                'employee' => $employee,
                'operator_tasks' => [
                    'completed' => $completedOperator,
                    'total' => $totalOperator
                ],
                'finisher_tasks' => [
                    'completed' => $completedFinisher,
                    'total' => $totalFinisher
                ],
                'qc_tasks' => [
                    'completed' => $completedQc,
                    'total' => $totalQc
                ],
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'completion_rate' => round($completionRate, 2)
            ];
        }

        return view('page.report.performance', compact('monthlyStats'));
    }

    public function daily()
    {
        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $salesId = $sales->id;
        $date = date('Y-m-d');
        
        $antrians = Antrian::with(['customer', 'sales', 'payment', 'job', 'order'])
            ->whereDate('created_at', $date)
            ->where('sales_id', $salesId)
            ->get();

        $totalOmset = 0;
        $totalOrders = count($antrians);
        $totalCustomers = $antrians->unique('customer_id')->count();
        
        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
        }

        return view('page.report.daily', compact('antrians', 'totalOmset', 'totalOrders', 'totalCustomers', 'date'));
    }
}
