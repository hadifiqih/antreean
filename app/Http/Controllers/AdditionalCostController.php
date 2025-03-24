<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdditionalCost;

class AdditionalCostController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'payment_transaction_id' => 'required|exists:payment_transactions,id',
            'type' =>'required|in:installation,shipping,packing,ppn,pph',
            'amount' =>'required|numeric|min:1',
            'description' =>'nullable|string'
        ]);

        try {
            $additionalCost = AdditionalCost::create($validatedData);
            return back()->with('success', 'Biaya tambahan berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Gagal menambahkan biaya tambahan. Silakan coba lagi.');
        }
    }
}
