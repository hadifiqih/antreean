<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;

class InstallmentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'payment_transaction_id' => 'required|exists:payment_transactions,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,transfer',
            'proof_file' =>'nullable|file|mimes:jpeg,png,pdf|max:2048'
        ]);

        try {
            $paymentTransaction = PaymentTransaction::findOrFail($validatedData['payment_transaction_id']);
            if ($paymentTransaction->payment_status === 'paid') {
                return response()->json(['message' => 'Payment transaction is already paid.'], 400);
            }
            $installment = new Installment;
            $installment->payment_transaction_id = $validatedData['payment_transaction_id'];
            $installment->amount = $validatedData['amount'];
            $installment->payment_method = $validatedData['payment_method'];

            if ($request->hasFile('proof_file')) {
                $proofFile = $request->file('proof_file');
                $proofFileName = time() . '_' . $proofFile->getClientOriginalName();
                $proofFile->move(public_path('storage/bukti-pembayaran'), $proofFileName);
                $installment->proof_file = $proofFileName;
            }
            $installment->save();

            $paymentTransaction->updateStatusPayment();
        } catch (Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat pembayaran.');
        }

        return back()->with('success', 'Pembayaran berhasil dibuat.');
    }
}
