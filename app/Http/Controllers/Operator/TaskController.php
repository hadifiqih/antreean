<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $employeeId = Auth::user()->employee->id;
        $tasks = Antrian::query()
            ->where(function($query) use ($employeeId) {
                $query->findInSet('operator_id', $employeeId)
                    ->orWhere('finisher_id', $employeeId)
                    ->orWhere('qc_id', $employeeId);
            })
            ->with(['customer', 'job'])
            ->where('status', 1)
            ->orderBy('end_job', 'asc')
            ->get();

        $rekanans = Antrian::query()
            ->where(function($query) {
                $query->findInSet('operator_id', 'rekanan')
                    ->orWhere('finisher_id', 'rekanan')
                    ->orWhere('qc_id', 'rekanan');
            })
            ->with(['customer', 'job'])
            ->where('status', 1)
            ->where('working_at', Auth::user()->employee->office)
            ->orderBy('end_job', 'asc')
            ->get();

        return view('operator.task', compact('tasks', 'rekanans'));
    }

    public function complete(Request $request, $ticket)
    {
        $antrian = Antrian::where('ticket_order', $ticket)->firstOrFail();
        $employeeId = Auth::user()->employee->id;

        // Verify if the employee is assigned to this task
        if (!in_array($employeeId, [$antrian->operator_id, $antrian->finisher_id, $antrian->qc_id])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update the task status
        $antrian->deadline_status = 1;
        $antrian->timer_stop = now();
        $antrian->status = 2; // Set status to completed
        $antrian->save();

        return response()->json(['message' => 'Tugas berhasil ditandai selesai']);
    }
}