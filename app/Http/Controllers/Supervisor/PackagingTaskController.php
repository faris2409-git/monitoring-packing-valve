<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\PackagingTask;
use App\Models\EngineValve;
use App\Models\User;
use Illuminate\Http\Request;

class PackagingTaskController extends Controller
{
    public function index()
    {
        $tasks = PackagingTask::with(['engineValve', 'operator'])
            ->latest()
            ->paginate(10);

        $totalCount = PackagingTask::count();
        $assignedCount = PackagingTask::where('status', 'assigned')->count();
        $inProgressCount = PackagingTask::whereIn('status', ['accepted', 'in_progress'])->count();
        $completedCount = PackagingTask::where('status', 'completed')->count();

        return view('supervisor.packaging-tasks.index', compact(
            'tasks', 'totalCount', 'assignedCount', 'inProgressCount', 'completedCount'
        ));
    }

    public function create()
    {
        $valves = EngineValve::orderBy('name')->get();
        $operators = User::where('role', 'operator')->orderBy('name')->get();

        return view('supervisor.packaging-tasks.create', compact('valves', 'operators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'engine_valve_id' => 'required|exists:engine_valves,id',
            'assigned_to' => 'required|exists:users,id',
            'quantity_target' => 'required|integer|min:1',
            'target_delivery_date' => 'required|date',
            'instruction' => 'nullable|string',
        ], [
            'engine_valve_id.required' => 'Pilih engine valve yang akan dikemas.',
            'assigned_to.required' => 'Pilih operator yang ditugaskan.',
            'quantity_target.required' => 'Target kuantitas wajib diisi.',
            'target_delivery_date.required' => 'Target tanggal pengiriman wajib ditentukan.',
        ]);

        $validated['task_code'] = 'TSK-' . now()->format('Ymd') . '-' . str_pad(PackagingTask::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'assigned';

        PackagingTask::create($validated);

        return redirect()->route('supervisor.packaging-tasks.index')
            ->with('success', 'Tugas packaging berhasil dibuat dengan target tanggal pengiriman.');
    }

    public function show(PackagingTask $packagingTask)
    {
        $packagingTask->load(['engineValve', 'operator', 'supervisor']);
        return view('supervisor.packaging-tasks.show', compact('packagingTask'));
    }

    public function destroy(PackagingTask $packagingTask)
    {
        if ($packagingTask->status !== 'assigned') {
            return back()->with('error', 'Tugas yang sudah diproses operator tidak bisa dihapus.');
        }

        $packagingTask->delete();
        return redirect()->route('supervisor.packaging-tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function monitoring(Request $request)
    {
        return redirect()->route('supervisor.packaging-tasks.index');
    }
}