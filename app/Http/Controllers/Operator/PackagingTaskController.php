<?php

namespace App\Http\Controllers\Operator;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\PackagingTask;
use Illuminate\Http\Request;

class PackagingTaskController extends Controller
{
    public function index()
    {
        $tasks = PackagingTask::with('engineValve')
            ->where('assigned_to', auth()->id())
            ->where('status', 'assigned')
            ->latest()
            ->paginate(10);

        return view('operator.packaging-tasks.index', compact('tasks'));
    }

    public function accept(PackagingTask $packagingTask)
    {
        if ($packagingTask->assigned_to !== auth()->id()) {
            return redirect()->route('operator.packaging-tasks.index')
                ->with('warning', 'Tugas ini tidak dialokasikan untuk akun Anda.');
        }

        $packagingTask->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return redirect()->route('operator.packaging-tasks.index')
            ->with('success', 'Tugas berhasil di-ACC, cek di Daftar Pengerjaan.');
    }

    public function work()
    {
        $tasks = PackagingTask::with('engineValve')
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['accepted', 'in_progress'])
            ->latest()
            ->paginate(10);

        return view('operator.packaging-tasks.work', compact('tasks'));
    }

    public function edit(PackagingTask $packagingTask)
    {
        if ($packagingTask->assigned_to !== auth()->id()) {
            return redirect()->route('operator.packaging-tasks.work')
                ->with('warning', 'Tugas ini tidak dialokasikan untuk akun Anda atau data telah diperbarui.');
        }

        return view('operator.packaging-tasks.edit', compact('packagingTask'));
    }

    public function update(Request $request, PackagingTask $packagingTask)
    {
        if ($packagingTask->assigned_to !== auth()->id()) {
            return redirect()->route('operator.packaging-tasks.work')
                ->with('warning', 'Tugas ini tidak dialokasikan untuk akun Anda atau data telah diperbarui.');
        }

        $validated = $request->validate([
            'quantity_packed' => 'required|integer|min:0|max:' . $packagingTask->quantity_target,
            'notes' => 'nullable|string',
            'action' => 'required|in:save,complete',
        ], [
            'quantity_packed.required' => 'Jumlah barang terpacking wajib diisi.',
            'quantity_packed.max' => 'Jumlah terpacking tidak boleh melebihi target ' . $packagingTask->quantity_target . ' unit.',
        ]);

        $action = $request->input('action');
        $packed = (int) $validated['quantity_packed'];
        $target = (int) $packagingTask->quantity_target;

        if ($action === 'complete') {
            if ($packed < $target) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Penyelesaian ditolak: Jumlah packing ({$packed} unit) belum mencapai target 100% ({$target} unit). Selesaikan sisa packing terlebih dahulu.");
            }

            $packagingTask->update([
                'quantity_packed' => $packed,
                'notes' => $validated['notes'],
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            return redirect()->route('operator.packaging-tasks.history')
                ->with('success', "Pekerjaan {$packagingTask->task_code} berhasil diselesaikan 100% dan dicatat di Riwayat Selesai.");
        }

        $packagingTask->update([
            'quantity_packed' => $packed,
            'notes' => $validated['notes'],
            'status' => 'in_progress',
        ]);

        return redirect()->route('operator.packaging-tasks.work')
            ->with('success', "Progres sementara tugas {$packagingTask->task_code} ({$packed}/{$target} unit) berhasil disimpan.");
    }

    public function history()
    {
        $tasks = PackagingTask::with('engineValve')
            ->where('assigned_to', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->paginate(10);

        return view('operator.packaging-tasks.history', compact('tasks'));
    }

    public function destroy(PackagingTask $packagingTask)
    {
        if ($packagingTask->assigned_to !== auth()->id() || $packagingTask->status !== 'completed') {
            return redirect()->route('operator.packaging-tasks.history')
                ->with('warning', 'Data riwayat tidak ditemukan atau bukan milik akun Anda.');
        }

        $taskCode = $packagingTask->task_code;
        $packagingTask->delete();

        return redirect()->route('operator.packaging-tasks.history')
            ->with('success', "Data riwayat pengerjaan {$taskCode} berhasil dihapus.");
    }

    public function exportPdf(PackagingTask $packagingTask)
    {
        if ($packagingTask->assigned_to !== auth()->id()) {
            return redirect()->route('operator.packaging-tasks.history')
                ->with('warning', 'Anda tidak memiliki hak akses untuk mengunduh dokumen ini.');
        }

        $pdf = Pdf::loadView('operator.packaging-tasks.pdf', compact('packagingTask'));
        return $pdf->download('hasil-packaging-' . $packagingTask->task_code . '.pdf');
    }
}