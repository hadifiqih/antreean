<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Sales;
use App\Models\DailyOffer;
use App\Models\ActivityType;
use App\Models\DailyReport;
use App\Models\DailyActivity;
use Illuminate\Http\Request;
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
        $sales = Sales::where('user_id', Auth::id())->first();
        $reports = DailyReport::with(['activities.activityType', 'offers.offer'])
            ->where('sales_id', $sales->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('sales.reports.index', compact('reports'));
    }

    public function create()
    {
        $activityTypes = ActivityType::all();
        $offers = Offer::where('sales_id', Auth::user()->sales->id)
            ->whereDoesntHave('dailyOffers')
            ->get();
        
        return view('sales.reports.create', compact('activityTypes', 'offers'));
    }

    public function store(DailyReportRequest $request)
    {
        DB::beginTransaction();
        try {
            $report = DailyReport::create([
                'sales_id' => Auth::user()->sales->id,
                'user_id' => Auth::id(),
                'omset' => $request->omset
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
                    DailyOffer::create([
                        'daily_report_id' => $report->id,
                        'offer_id' => $offer['id'],
                        'is_prospect' => $offer['is_prospect'] ?? false,
                        'updates' => $offer['updates'] ?? null
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
        return view('sales.reports.show', compact('report'));
    }

    public function edit(DailyReport $report)
    {
        $activityTypes = ActivityType::all();
        $offers = Offer::where('sales_id', Auth::user()->sales->id)
            ->whereDoesntHave('dailyOffers', function($query) use ($report) {
                $query->where('daily_report_id', '!=', $report->id);
            })
            ->get();
        
        return view('sales.reports.edit', compact('report', 'activityTypes', 'offers'));
    }

    public function update(DailyReportRequest $request, DailyReport $report)
    {
        DB::beginTransaction();
        try {
            $report->update([
                'omset' => $request->omset
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
                    DailyOffer::create([
                        'daily_report_id' => $report->id,
                        'offer_id' => $offer['id'],
                        'is_prospect' => $offer['is_prospect'] ?? false,
                        'updates' => $offer['updates'] ?? null
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