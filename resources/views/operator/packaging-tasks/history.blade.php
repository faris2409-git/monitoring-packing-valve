@extends('layouts.app')
@section('title', 'Riwayat Packaging Selesai')

@section('content')
@php
    $totalCompleted = $tasks->total();
    $totalValvesPacked = \App\Models\PackagingTask::where('assigned_to', auth()->id())
        ->where('status', 'completed')
        ->sum('quantity_packed');
@endphp

<div class="fade-in">
    <!-- Header Banner -->
    <div class="page-header-box">
        <div class="d-flex align-items-center gap-3">
            <div class="page-header-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <h4 class="page-header-title">Riwayat Selesai & Sertifikat Serah Terima</h4>
                <p class="page-header-desc">Daftar arsip seluruh tugas packaging yang telah tuntas dikerjakan dan siap diekspor menjadi dokumen PDF resmi.</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('operator.packaging-tasks.work') }}" class="btn btn-cyber-outline">
                <i class="bi bi-tools me-1"></i> Pengerjaan Aktif
            </a>
            <a href="{{ route('operator.dashboard') }}" class="btn btn-cyber-outline">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Metric Chips to prevent emptiness -->
    <div class="stat-chip-group">
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-chip-val" style="color: #34d399;">{{ $totalCompleted }} Tugas</div>
                <div class="stat-chip-title">Telah Selesai 100%</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(0, 245, 212, 0.15); color: var(--accent);">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <div class="stat-chip-val" style="color: var(--accent);">{{ number_format($totalValvesPacked) }} Unit</div>
                <div class="stat-chip-title">Total Valve Terpacking</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8;">
                <i class="bi bi-file-earmark-pdf-fill"></i>
            </div>
            <div>
                <div class="stat-chip-val">E-DOKUMEN</div>
                <div class="stat-chip-title">Bukti Berformat PDF</div>
            </div>
        </div>
    </div>

    <!-- History Table Card -->
    <div class="cyber-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-archive text-accent"></i> Arsip Tugas Selesai
                </h5>
                <span class="text-muted small">Unduh surat tanda bukti serah terima packaging untuk arsip fisik stasiun</span>
            </div>

            <div class="search-box-modern flex-grow-1 flex-sm-grow-0" style="min-width: 240px;">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control form-control-sm table-search-input" placeholder="Cari kode atau valve...">
            </div>
        </div>

        <div class="table-responsive" data-filterable-table>
            <table class="table table-cyber align-middle">
                <thead>
                    <tr>
                        <th width="180">Kode Tugas</th>
                        <th>Engine Valve</th>
                        <th>Qty Selesai</th>
                        <th>Target Kirim</th>
                        <th>Waktu Penyelesaian</th>
                        <th>Status Mutu</th>
                        <th class="text-end" width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="mono-font fw-bold text-accent small">{{ $task->task_code }}</span>
                                    <button type="button" class="copy-code-btn" onclick="copyTaskCode('{{ $task->task_code }}', this)" title="Salin Kode">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-white small">{{ $task->engineValve->name }}</div>
                                <span class="mono-font text-muted" style="font-size: 0.72rem;">{{ $task->engineValve->code }}</span>
                            </td>
                            <td class="mono-font small text-white fw-bold">
                                {{ $task->quantity_packed }} Unit
                            </td>
                            <td>
                                @if($task->target_delivery_date)
                                    <span class="mono-font text-muted small">
                                        <i class="bi bi-calendar2-check text-accent me-1"></i>
                                        {{ \Carbon\Carbon::parse($task->target_delivery_date)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="small mono-font text-white">
                                    {{ $task->completed_at?->format('d M Y') }}
                                </div>
                                <div class="text-muted mono-font" style="font-size: 0.7rem;">
                                    {{ $task->completed_at?->format('H:i') }} WIB
                                </div>
                            </td>
                            <td>
                                <span class="badge d-inline-flex align-items-center gap-1 py-1 px-2" style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-size: 0.72rem;">
                                    <i class="bi bi-shield-check"></i> QC PASSED
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                                    <a href="{{ route('operator.packaging-tasks.pdf', $task) }}" class="btn btn-sm btn-cyber py-1 px-2" title="Unduh Dokumen Bukti PDF">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                                    </a>
                                    <form action="{{ route('operator.packaging-tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data riwayat tugas {{ $task->task_code }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus Riwayat Selesai">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-archive fs-2 d-block mb-2 text-muted"></i>
                                Belum ada riwayat tugas packaging yang diselesaikan.
                                <div class="mt-2 text-muted small">
                                    Setelah Anda menyelesaikan target packaging di stasiun kerja, riwayatnya akan tersimpan di sini.
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr class="empty-filter-row" style="display: none;">
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
                            Tidak ada riwayat pengerjaan yang cocok dengan kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Menampilkan {{ $tasks->firstItem() ?? 0 }} - {{ $tasks->lastItem() ?? 0 }} dari {{ $tasks->total() }} riwayat tugas</span>
            <div>
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection