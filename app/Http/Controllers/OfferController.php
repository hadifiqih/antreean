<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Offer;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role !== 'sales') {
            $offers = Offer::with(['job', 'sales', 'platform'])
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }else {
            $offers = Offer::with(['job', 'sales', 'platform'])
            ->where('sales_id', Auth::user()->sales->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }
        
        return view('sales.offers.index', compact('offers'));
    }

    public function create()
    {
        $jobs = Job::all();
        $platforms = Platform::orderBy('platform_name')->get();
        return view('sales.offers.create', compact('jobs', 'platforms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'platform_id' => 'nullable|exists:platforms,id',
            'price' => 'required|numeric|min:0',
            'qty' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $offer = new Offer();
        $offer->job_id = $request->job_id;
        $offer->platform_id = $request->platform_id;
        $offer->sales_id = Auth::user()->sales->id;
        $offer->price = $request->price;
        $offer->qty = $request->qty;
        $offer->total = $request->total;
        $offer->description = $request->description;
        $offer->save();

        return redirect()->route('sales.offers.index')
            ->with('success', 'Penawaran berhasil dibuat');
    }

    public function edit(Offer $offer)
    {
        $this->authorize('update', $offer);
        $jobs = Job::all();
        $platforms = Platform::all();
        return view('sales.offers.edit', compact('offer', 'jobs', 'platforms'));
    }

    public function update(Request $request, Offer $offer)
    {
        $this->authorize('update', $offer);
        
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'platform_id' => 'nullable|exists:platforms,id',
            'price' => 'required|numeric|min:0',
            'qty' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $offer->job_id = $request->job_id;
        $offer->platform_id = $request->platform_id;
        $offer->price = $request->price;
        $offer->qty = $request->qty;
        $offer->total = $request->total;
        $offer->description = $request->description;
        $offer->save();

        return redirect()->route('sales.offers.index')
            ->with('success', 'Penawaran berhasil diperbarui');
    }

    public function destroy(Offer $offer)
    {
        $this->authorize('delete', $offer);
        
        if ($offer->dailyOffers()->exists()) {
            return back()->with('error', 'Cannot delete offer that is used in daily reports');
        }

        $offer->delete();
        return redirect()->route('sales.offers.index')
            ->with('success', 'Offer deleted successfully');
    }
}