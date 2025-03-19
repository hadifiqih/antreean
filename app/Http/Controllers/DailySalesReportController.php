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
use App\Http\Requests\DailyReportRequest;

class DailySalesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->role !== 'sales') {
            $reports = DailyReport::with(['activities.activityType', 'offers.offer'])
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }else{
            $sales = Sales::where('user_id', Auth::id())->first();
            $reports = DailyReport::with(['activities.activityType', 'offers.offer'])
                ->where('sales_id', $sales->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        
        return view('sales.reports.index', compact('reports'));
    }

    public function create()
    {
        $activityTypes = ActivityType::all();
        if(Auth::user()->role !== 'sales') {
            $offers = Offer::where('is_closing', false)
                ->whereDoesntHave('dailyOffers', function($query) {
                    $query->whereDate('created_at', date('Y-m-d'));
                })
                ->get();
        }else{
            
            $offers = Offer::where('sales_id', Auth::user()->sales->id)
                ->where('is_closing', false)
                ->whereDoesntHave('dailyOffers', function($query) {
                    $query->whereDate('created_at', date('Y-m-d'));
                })
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
                'omset' => $request->omset,
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

            // Store offers
            if ($request->has('offers')) {
                foreach ($request->offers as $offer) {
                    $updates = isset($offer['updates']) ? explode(',', $offer['updates']) : null;
                    
                    DailyOffer::create([
                        'daily_report_id' => $report->id,
                        'offer_id' => $offer['id'],
                        'is_prospect' => isset($offer['is_prospect']) ? true : false,
                        'is_closing' => isset($offer['is_closing']) ? true : false,
                        'updates' => $updates
                    ]);

                    // Update the corresponding offer with the updates
                    Offer::where('id', $offer['id'])->update([
                        'updates' => $updates,
                        'is_closing' => isset($offer['is_closing']) ? true : false,
                        'is_prospect' => isset($offer['is_prospect']) ? true : false
                    ]);
                }
            }

            // Store ads reports
            if ($request->has('ads')) {
                foreach ($request->ads as $index => $adsData) {
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
            return redirect()->route('sales.reports.index')
                ->with('success', 'Daily report created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error creating report: ' . $e->getMessage());
        }
    }

    public function show(DailyReport $report)
    {
        $report->load(['activities.activityType', 'offers.offer']);
        $antrians = Antrian::with(['job'])->where('sales_id', $report->sales_id)
            ->whereDate('created_at', $report->created_at->format('Y-m-d'))
            ->get();
            
        return view('sales.reports.show', compact('report', 'antrians'));
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

    public function update(Request $request, DailyReport $report)
    {
        DB::beginTransaction();
        try {
            $report->update([
                'omset' => $request->omset,
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

            // Update offers
            $report->offers()->delete();
            if ($request->has('offers')) {
                foreach ($request->offers as $offer) {
                    $updates = isset($offer['updates']) ? explode(',', $offer['updates']) : null;
                    
                    DailyOffer::create([
                        'daily_report_id' => $report->id,
                        'offer_id' => $offer['id'],
                        'is_prospect' => isset($offer['is_prospect']) ? true : false,
                        'is_closing' => isset($offer['is_closing']) ? true : false,
                        'updates' => $updates
                    ]);

                    Offer::where('id', $offer['id'])->update([
                        'updates' => $updates,
                        'is_closing' => isset($offer['is_closing']) ? true : false,
                        'is_prospect' => isset($offer['is_prospect']) ? true : false
                    ]);
                }
            }

            // Update ads reports
            $report->adsReports()->delete();
            if ($request->has('ads')) {
                foreach ($request->ads as $index => $adsData) {
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
        $startDate = $request->get('start_date', date('Y-m-d', strtotime('-30 days')));
        $endDate = $request->get('end_date', date('Y-m-d'));
        
        $sales = Sales::where('user_id', Auth::id())->first();
        
        // Get reports for the date range
        $reports = DailyReport::with(['activities.activityType', 'offers'])
            ->where('sales_id', $sales->id)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        // Calculate summary statistics
        $summary = [
            'total_omset' => $reports->sum('omset'),
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
            
            // Activity types distribution with proper counting
            'activity_types' => ActivityType::select('id', 'name')
                ->withCount(['dailyActivities' => function($query) use ($reports) {
                    $query->whereIn('daily_report_id', $reports->pluck('id'));
                }])
                ->having('daily_activities_count', '>', 0)
                ->get(),
            
            // Daily omset data
            'daily_omset' => $reports->map(function($report) {
                return [
                    'date' => $report->created_at->format('d M'),
                    'omset' => $report->omset
                ];
            })->sortBy('date')->values()
        ];

        return view('sales.reports.summary', compact('summary'));
    }
}