<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\PackagingTask;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with('items.packagingTask.engineValve')
            ->where('created_by', auth()->id())
            ->latest()
            ->paginate(10);

        return view('supervisor.shipments.index', compact('shipments'));
    }

    public function create()
    {
        // Hanya tugas selesai yang belum pernah dimasukkan ke surat jalan manapun
        $availableTasks = PackagingTask::with('engineValve')
            ->where('created_by', auth()->id())
            ->where('status', 'completed')
            ->whereDoesntHave('shipmentItems')
            ->get();

        return view('supervisor.shipments.create', compact('availableTasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_name' => 'required|string',
            'vehicle_no' => 'nullable|string',
            'shipment_date' => 'required|date',
            'notes' => 'nullable|string',
            'task_ids' => 'required|array|min:1',
            'task_ids.*' => 'exists:packaging_tasks,id',
        ]);

        $shipment = Shipment::create([
            'shipment_code' => 'SJ-' . now()->format('Ymd') . '-' . str_pad(Shipment::count() + 1, 4, '0', STR_PAD_LEFT),
            'driver_name' => $validated['driver_name'],
            'vehicle_no' => $validated['vehicle_no'],
            'shipment_date' => $validated['shipment_date'],
            'created_by' => auth()->id(),
            'notes' => $validated['notes'],
        ]);

        foreach ($validated['task_ids'] as $taskId) {
            $task = PackagingTask::find($taskId);
            $shipment->items()->create([
                'packaging_task_id' => $taskId,
                'quantity' => $task->quantity_packed,
            ]);
        }

        return redirect()->route('supervisor.shipments.index')
            ->with('success', 'Surat jalan berhasil dibuat.');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load('items.packagingTask.engineValve', 'creator');
        return view('supervisor.shipments.show', compact('shipment'));
    }

    public function exportPdf(Shipment $shipment)
    {
        $shipment->load('items.packagingTask.engineValve', 'creator');
        $pdf = Pdf::loadView('supervisor.shipments.pdf', compact('shipment'));
        return $pdf->download('surat-jalan-' . $shipment->shipment_code . '.pdf');
    }
}