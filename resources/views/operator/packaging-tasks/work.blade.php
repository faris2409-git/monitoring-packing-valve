@extends('layouts.app')
@section('title', 'Daftar Pengerjaan Aktif')

@section('content')
@php
    $totalActive = $tasks->total();
    $totalPendingACC = \App\Models\PackagingTask::where('assigned_to', auth()->id())
        ->where('status', 'assigned')
        ->count();
@endphp

<div class="fade-in">
    <!-- Header Banner -->
    <div class="page-header-box">
        <div class="d-flex align-items-center gap-3">
            <div class="page-header-icon" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8;">
                <i class="bi bi-tools"></i>
            </div>
            <div>
                <h4 class="page-header-title">Stasiun Pengerjaan Packaging Aktif</h4>
                <p class="page-header-desc">Input jumlah hasil fisik valve yang telah lolos QC, diberi minyak anti-karat, dan dikemas blister.</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if($totalPendingACC > 0)
                <a href="{{ route('operator.packaging-tasks.index') }}" class="btn btn-cyber">
                    <i class="bi bi-bell-fill me-1"></i> {{ $totalPendingACC }} Tugas Perlu ACC
                </a>
            @endif
            <a href="{{ route('operator.packaging-tasks.history') }}" class="btn btn-cyber-outline">
                <i class="bi bi-clock-history me-1"></i> Riwayat Selesai
            </a>
            <a href="{{ route('operator.dashboard') }}" class="btn btn-cyber-outline">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Metric Chips to prevent emptiness -->
    <div class="stat-chip-group">
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8;">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <div>
                <div class="stat-chip-val">{{ $totalActive }} Tugas</div>
                <div class="stat-chip-title">Dalam Pengerjaan Anda</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(0, 245, 212, 0.15); color: var(--accent);">
                <i class="bi bi-check-all"></i>
            </div>
            <div>
                <div class="stat-chip-val">QC 4-TITIK</div>
                <div class="stat-chip-title">Standar Inspeksi Katup</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <div class="stat-chip-val">ANTI-RUST</div>
                <div class="stat-chip-title">VCI Film Protective</div>
            </div>
        </div>
    </div>

    <!-- Active Work Table Card -->
    <div class="cyber-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-sliders text-accent"></i> Antrean Pengerjaan Fisik
                </h5>
                <span class="text-muted small">Pilih tugas lalu klik tombol Input untuk memperbarui kuantitas atau menyelesaikan tugas</span>
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
                        <th>Target Qty</th>
                        <th>Target Kirim</th>
                        <th>Hasil Terpacking</th>
                        <th>Progres Selesai</th>
                        <th>Status Kerja</th>
                        <th class="text-end" width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        @php
                            $percent = $task->quantity_target > 0
                                ? min(100, round(($task->quantity_packed / $task->quantity_target) * 100))
                                : 0;
                        @endphp
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
                            <td class="mono-font small text-white">{{ $task->quantity_target }}</td>
                            <td>
                                @if($task->target_delivery_date)
                                    @php
                                        $tDate = \Carbon\Carbon::parse($task->target_delivery_date)->startOfDay();
                                        $isPast = \Carbon\Carbon::now()->startOfDay()->gt($tDate);
                                        $isToday = \Carbon\Carbon::now()->startOfDay()->eq($tDate);
                                    @endphp
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar2-check text-accent"></i>
                                        <span class="mono-font fw-semibold text-white small">
                                            {{ \Carbon\Carbon::parse($task->target_delivery_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    @if($isPast)
                                        <span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #f87171; font-size: 0.65rem;">Terlewat</span>
                                    @elseif($isToday)
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; font-size: 0.65rem;">Hari Ini</span>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="mono-font small text-accent fw-bold">{{ $task->quantity_packed }}</td>
                            <td style="min-width: 120px;">
                                <div class="progress-modern mb-1">
                                    <div class="progress-bar-modern" style="width: {{ $percent }}%; height: 6px;"></div>
                                </div>
                                <span class="mono-font text-muted" style="font-size: 0.7rem;">{{ $percent }}% Selesai</span>
                            </td>
                            <td>
                                @if ($task->status === 'accepted')
                                    <span class="badge d-inline-flex align-items-center gap-1 py-1 px-2" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8; font-size: 0.72rem;">
                                        <i class="bi bi-clock-history"></i> Siap Dikerjakan
                                    </span>
                                @else
                                    <span class="badge d-inline-flex align-items-center gap-1 py-1 px-2" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; font-size: 0.72rem;">
                                        <i class="bi bi-gear-wide"></i> Sedang Dikerjakan
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('operator.packaging-tasks.edit', $task) }}" class="btn btn-sm btn-cyber py-1 px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Input Progres
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-check2-all fs-2 d-block mb-2 text-accent"></i>
                                Tidak ada pengerjaan aktif saat ini.
                                <div class="mt-2 text-muted small">
                                    Semua tugas yang telah diterima sudah selesai atau belum ada tugas yang di-ACC.
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('operator.packaging-tasks.index') }}" class="btn btn-sm btn-cyber">
                                        <i class="bi bi-inbox-fill me-1"></i> Cek Tugas Masuk Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr class="empty-filter-row" style="display: none;">
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
                            Tidak ada data pengerjaan yang cocok dengan pencarian Anda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Menampilkan {{ $tasks->firstItem() ?? 0 }} - {{ $tasks->lastItem() ?? 0 }} dari {{ $tasks->total() }} pengerjaan aktif</span>
            <div>
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection