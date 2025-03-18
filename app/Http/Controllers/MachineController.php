<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::all();
        return view('master.machine.index', compact('machines'));
    }

    public function create()
    {
        return view('master.machine.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_code' => 'required|unique:machines',
            'name' => 'required',
            'type' => 'required',
            'description' => 'nullable',
            'is_active' => 'boolean'
        ]);

        Machine::create($validated);

        return redirect()->route('machine.index')
            ->with('success', 'Machine created successfully.');
    }

    public function edit(Machine $machine)
    {
        return view('master.machine.edit', compact('machine'));
    }

    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'machine_code' => 'required|unique:machines,machine_code,' . $machine->id,
            'name' => 'required',
            'type' => 'required',
            'description' => 'nullable',
            'is_active' => 'boolean'
        ]);

        $machine->update($validated);

        return redirect()->route('machine.index')
            ->with('success', 'Machine updated successfully.');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();

        return redirect()->route('machine.index')
            ->with('success', 'Machine deleted successfully.');
    }
}