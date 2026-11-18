<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\EngineValve;
use Illuminate\Http\Request;

class EngineValveController extends Controller
{
    public function index()
    {
        $valves = EngineValve::latest()->paginate(10);
        $totalCount = EngineValve::count();
        $intakeCount = EngineValve::where('type', 'Intake')->count();
        $exhaustCount = EngineValve::where('type', 'Exhaust')->count();

        return view('supervisor.engine-valves.index', compact('valves', 'totalCount', 'intakeCount', 'exhaustCount'));
    }

    public function create()
    {
        return view('supervisor.engine-valves.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:engine_valves,code',
            'name' => 'required|string|max:100',
            'type' => 'required|in:Intake,Exhaust',
            'spec' => 'nullable|string',
        ], [
            'code.required' => 'Kode barang wajib diisi.',
            'code.unique' => 'Kode barang ini sudah terdaftar. Gunakan kode barang yang lain.',
            'name.required' => 'Nama (misal: Yamaha, AHM, Suzuki) wajib diisi.',
            'type.required' => 'Pilih tipe katup (Intake atau Exhaust).',
            'type.in' => 'Tipe katup harus berupa Intake atau Exhaust.',
        ]);

        EngineValve::create($validated);

        return redirect()->route('supervisor.engine-valves.index')
            ->with('success', 'Data engine valve baru berhasil ditambahkan.');
    }

    public function edit(EngineValve $engineValve)
    {
        return view('supervisor.engine-valves.edit', compact('engineValve'));
    }

    public function update(Request $request, EngineValve $engineValve)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:engine_valves,code,' . $engineValve->id,
            'name' => 'required|string|max:100',
            'type' => 'required|in:Intake,Exhaust',
            'spec' => 'nullable|string',
        ], [
            'code.required' => 'Kode barang wajib diisi.',
            'code.unique' => 'Kode barang ini sudah digunakan oleh data lain.',
            'name.required' => 'Nama (misal: Yamaha, AHM, Suzuki) wajib diisi.',
            'type.required' => 'Pilih tipe katup (Intake atau Exhaust).',
            'type.in' => 'Tipe katup harus berupa Intake atau Exhaust.',
        ]);

        $engineValve->update($validated);

        return redirect()->route('supervisor.engine-valves.index')
            ->with('success', 'Data engine valve berhasil diperbarui.');
    }

    public function destroy(EngineValve $engineValve)
    {
        $engineValve->delete();

        return redirect()->route('supervisor.engine-valves.index')
            ->with('success', 'Data engine valve berhasil dihapus.');
    }
}