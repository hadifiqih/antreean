<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Offer;
use App\Models\Sales;
use GuzzleHttp\Client;
use App\Models\Antrian;
use App\Models\AdsReport;
use App\Models\DailyOffer;
use App\Models\DailyReport;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use App\Models\DailyActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\DailyReportRequest;

class DailySalesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = DailyReport::with(['activities.activityType', 'sales']);

        $offers = Offer::whereDoesntHave('dailyOffers', function($query) {
                $query->whereDate('created_at', date('Y-m-d'));
            })
            ->orderBy('created_at', 'desc')
            ->count();

        if (Auth::user()->role === 'sales') {
            $query->where('sales_id', Auth::user()->sales->id);
        } else {
            // Filter by selected sales
            if ($request->sales_id) {
                $query->where('sales_id', $request->sales_id);
            }
        }

        $reports = $query->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->orderBy('created_at', 'desc')
            ->get();

        $antrians = Antrian::with(['sales'])
            ->when(Auth::user()->role == 'sales', function($query) {
                return $query->where('sales_id', Auth::user()->sales->id);
            })
            ->when($request->sales_id, function($query) use ($request) {
                return $query->where('sales_id', $request->sales_id);
            })
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(omset) as daily_omset'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $omsets = $antrians->pluck('daily_omset', 'date')->toArray();

        // Get all sales for filter
        $salesList = [];
        if (Auth::user()->role !== 'sales') {
            $salesList = Sales::with('user')->get();
        }

        return view('sales.reports.index', compact('reports', 'antrians', 'salesList', 'omsets', 'offers'));
    }

    public function create()
    {
        $activityTypes = ActivityType::all();

        if(Auth::user()->role !== 'sales') {
            $offers = Offer::whereDoesntHave('dailyOffers', function($query) {
                    $query->whereDate('created_at', date('Y-m-d'));
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }else{
            $offers = Offer::where('sales_id', Auth::user()->sales->id)
                ->whereDoesntHave('dailyOffers', function($query) {
                    $query->whereDate('created_at', date('Y-m-d'));
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        try {
            $client = new Client();
            $response = $client->request('GET', config('services.api_url_antrian') . '/api/ads/sales/' . Auth::user()->sales->id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_TOKEN')
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $ads = json_decode($response->getBody(), true);
            } else {
                $ads = [];
                \Log::error('Failed to fetch ads: ' . $response->getBody());
            }
        } catch (\Exception $e) {
            $ads = [];
            \Log::error('Exception occurred while fetching ads: ' . $e->getMessage());
        }

        return view('sales.reports.create', compact('activityTypes', 'offers', 'ads'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $report = DailyReport::create([
                'sales_id' => Auth::user()->sales->id,
                'user_id' => Auth::id(),
                'kendala' => $request->kendala ?? null,
                'agendas' => $request->agendas ? array_map('trim', explode(',', $request->agendas)) : null
            ]);

            // Store activities
            foreach ($request->activities as $activity) {
                DailyActivity::create([
                    'daily_report_id' => $report->id,
                    'activity_type_id' => $activity['activity_type_id'],
                    'description' => $activity['description'],
                    'amount' => $activity['amount']
                ]);
            }

            // Store ads reports
            if ($request->has('ads')) {
                foreach ($request->ads as $adsData) {
                    AdsReport::create([
                        'daily_report_id' => $report->id,
                        'ads_id' => $adsData['ads_id'],
                        'platform_id' => $adsData['platform_id'],
                        'job_name' => $adsData['job_name'],
                        'lead_amount' => $adsData['lead_amount'] ?? 0,
                        'total_omset' => $adsData['total_omset'] ?? 0,
                        'analisa' => $adsData['analisa'] ?? null,
                        'kendala' => $adsData['kendala'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sales.reports.index')->with('success', 'Daily report created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error creating report: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $report = DailyReport::with(['activities.activityType', 'offers.offer'])->findOrFail($id);

        if(Auth::user()->role == 'sales') {
            $antrians = Antrian::with(['job'])
            ->where('sales_id', Auth::user()->sales->id)
            ->whereDate('created_at', $report->created_at)
            ->get();
        }else{
            $antrians = Antrian::with(['job'])
            ->where('sales_id', $report->sales_id)
            ->whereDate('created_at', $report->created_at)
            ->get();
        }

        $todayOmset = $antrians->sum('omset');

        return view('sales.reports.show', compact('report', 'antrians', 'todayOmset'));
    }

    public function edit(DailyReport $report)
    {
        $activityTypes = ActivityType::all();
        $offers = Offer::where('sales_id', $report->sales_id)
            ->whereDoesntHave('dailyOffers', function($query) use ($report) {
                $query->where('daily_report_id', '!=', $report->id);
            })
            ->get();

        $adsReports = $report->adsReports()->get();

        try {
            $client = new Client();
            $response = $client->request('GET', config('services.api_url_antrian') . '/api/ads/sales/' . $report->sales_id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_TOKEN')
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $ads = json_decode($response->getBody(), true);
            } else {
                $ads = [];
                \Log::error('Failed to fetch ads: ' . $response->getBody());
            }
        } catch (\Exception $e) {
            $ads = [];
            \Log::error('Exception occurred while fetching ads: ' . $e->getMessage());
        }

        return view('sales.reports.edit', compact('report', 'activityTypes', 'offers', 'ads', 'adsReports'));
    }

    public function update(Request $request, $report)
    {
        $report = DailyReport::findOrFail($report);
        DB::beginTransaction();
        try {
            $report->update([
                'kendala' => $request->kendala,
                'agendas' => $request->agendas ? array_map('trim', explode(',', $request->agendas)) : null
            ]);

            // Update activities
            $report->activities()->delete();
            foreach ($request->activities as $activity) {
                DailyActivity::create([
                    'daily_report_id' => $report->id,
                    'activity_type_id' => $activity['activity_type_id'],
                    'description' => $activity['description'],
                    'amount' => $activity['amount']
                ]);
            }

            // Update ads reports
            $report->adsReports()->delete();
            if ($request->has('ads')) {
                foreach ($request->ads as $adsData) {
                    AdsReport::create([
                        'daily_report_id' => $report->id,
                        'ads_id' => $adsData['ads_id'],
                        'platform_id' => $adsData['platform_id'],
                        'job_name' => $adsData['job_name'],
                        'lead_amount' => $adsData['lead_amount'] ?? 0,
                        'total_omset' => $adsData['total_omset'] ?? 0,
                        'analisa' => $adsData['analisa'] ?? null,
                        'kendala' => $adsData['kendala'] ?? null
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sales.reports.index')
                ->with('success', 'Daily report updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating report: ' . $e->getMessage());
        }
    }

    public function destroy(DailyReport $report)
    {
        DB::beginTransaction();
        try {
            $report->activities()->delete();
            $report->offers()->delete();
            $report->adsReports()->delete();
            $report->delete();

            DB::commit();
            return redirect()->route('sales.reports.index')
                ->with('success', 'Daily report deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error deleting report: ' . $e->getMessage());
        }
    }

    public function summary(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        // Initialize query for sales
        $salesQuery = Sales::query();

        if (Auth::user()->role === 'sales') {
            $salesQuery->where('user_id', Auth::id());
        } elseif ($request->has('sales_id') && $request->sales_id) {
            $salesQuery->where('id', $request->sales_id);
        }

        $salesIds = $salesQuery->pluck('id');

        // Get reports for the date range
        $reports = DailyReport::with(['activities.activityType', 'offers'])
            ->whereIn('sales_id', $salesIds)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        $totalOmset = Antrian::whereIn('sales_id', $salesIds)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(omset) as daily_omset'))
            ->groupBy('date')
            ->get();

        $monthlyOmset = Antrian::whereIn('sales_id', $salesIds)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('omset');

        // Calculate activity types distribution
        $activityTypeStats = DB::table('activity_types')
            ->leftJoin('daily_activities', 'activity_types.id', '=', 'daily_activities.activity_type_id')
            ->leftJoin('daily_reports', 'daily_activities.daily_report_id', '=', 'daily_reports.id')
            ->whereIn('daily_reports.sales_id', $salesIds)
            ->whereBetween('daily_reports.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                'activity_types.id',
                'activity_types.name',
                DB::raw('COUNT(daily_activities.id) as daily_activities_count')
            )
            ->groupBy('activity_types.id', 'activity_types.name')
            ->having('daily_activities_count', '>', 0)
            ->get();

        // Calculate summary statistics
        $summary = [
            'total_omset' => $totalOmset,
            'monthly_omset' => $monthlyOmset,
            'total_activities' => $reports->sum(function($report) {
                return $report->activities->count();
            }),
            'total_offers' => $reports->sum(function($report) {
                return $report->offers->count();
            }),
            'prospect_rate' => $reports->sum(function($report) {
                $totalOffers = $report->offers->count();
                return $totalOffers > 0
                    ? ($report->offers->where('is_prospect', true)->count() / $totalOffers) * 100
                    : 0;
            }) / ($reports->count() ?: 1),

            // Updated activity types collection
            'activity_types' => $activityTypeStats,

            // Daily omset data
            'daily_omset' => $totalOmset->map(function($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('d M'),
                    'omset' => $item->daily_omset
                ];
            })->sortBy('date')->values()
        ];

        // Get salesList for filter if not sales role
        $salesList = [];
        if (Auth::user()->role !== 'sales') {
            $salesList = Sales::with('user')->get();
        }

        return view('sales.reports.summary', compact('summary', 'salesList'));
    }

    public function unlock(Request $request, $report)
    {
        try {
            $report = DailyReport::findOrFail($report);
            $report->is_locked = false;
            $report->save();

            return back()->with('success', 'Laporan berhasil dibuka!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error unlocking report: ' . $e->getMessage());
        }
    }

    public function lock(Request $request, $report)
    {
        try {
            $report = DailyReport::findOrFail($report);
            $report->is_locked = true;
            $report->save();

            return back()->with('success', 'Laporan berhasil dikunci!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error locking report: ' . $e->getMessage());
        }
    }

    private function getOmsetRetail($id)
    {
        $client = new Client();

        try {
            $response = $client->request('GET', config('services.api_url_antrian') . '/api/retail/sales/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_TOKEN')
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
