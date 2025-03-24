<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Antrian;
use App\Models\Payment;
use App\Models\Installment;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $awal = now()->startOfMonth();
        $akhir = now()->endOfMonth();

        if(Auth::user()->role == 'keuangan'){
            $downPayment = PaymentTransaction::with('antrian')
            ->where(function ($query) {
                $query->where('payment_status', 'partially_paid')
                    ->orWhere('payment_status', 'unpaid');
            })
            ->whereBetween('created_at', [$awal, $akhir])
            ->orderBy('created_at', 'desc')
            ->get();

            $fullPayment = PaymentTransaction::with('antrian')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$awal, $akhir])
            ->orderBy('created_at', 'desc')
            ->get();
        }else{
            $downPayment = PaymentTransaction::with('antrian')
                ->where('payment_status', 'partially_paid')
                ->whereBetween('created_at', [$awal, $akhir])
                ->whereHas('antrian', function ($query) {
                    $query->where('sales_id', Auth::user()->sales->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $fullPayment = PaymentTransaction::with('antrian')
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$awal, $akhir])
                ->whereHas('antrian', function ($query) {
                    $query->where('sales_id', Auth::user()->sales->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('page.payments.index', compact('downPayment', 'fullPayment'));
    }

    public function store(Request $request)
    {

        $antrian = Antrian::where('ticket_order', $request->order_number)->first();

        $status = $request->payment_amount == $request->omset ? 1 : 0;
        dd($antrian->ticket_order,$request->omset, $status);

        //Membuat $fileName dengan kondisi jika status = 1 maka nama file + ticket_order = fullpayment.jpg Jika tidak maka nama file + ticket_order = downpayment.jpg
        $file = $request->file('payment_proof');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $fileName = $status == 1 ? 'fullpayment'. $fileName : 'downpayment' . $fileName;
        $path = $file->storeAs('public/payment-proof', $fileName);

        $validated = $request->validate([
            'order_number' => 'required',
            'omset' => 'required|numeric|min:0',
            'payment_amount' => 'required|numeric|min:0|max:' . $request->omset,
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bank' => 'required'
        ]);

        $validated['payment_status'] = $validated['payment_amount'] == $validated['omset'] ? 1 : 0;
        $validated['payment_proof'] = $path; // Menyimpan path ke payment_proof dalam field yang sesuai

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Pembayaran dikonfirmasi !');
    }

    public function show($id)
    {
        $paymentTransaction = PaymentTransaction::with(['installments', 'additionalCosts'])->find($id);
        return view('page.payments.show', compact('paymentTransaction'));
    }

    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function validatePayment()
    {
        $installments = Installment::whereNull('validated_by')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->get(); // Ganti dengan model dan relasi yang sesuai
        return view('page.payments.validation', compact('installments'));
    }
}
