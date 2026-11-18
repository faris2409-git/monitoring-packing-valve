@extends('layouts.app')
@section('title', 'Daftar Tugas Menunggu ACC')

@section('content')
@php
    $totalPending = $tasks->total();
    $inProgressCount = \App\Models\PackagingTask::where('assigned_to', auth()->id())
        ->whereIn('status', ['accepted', 'in_progress'])
        ->count();
@endphp

<div class="fade-in">
    <!-- Header Banner -->
    <div class="page-header-box">
        <div class="d-flex align-items-center gap-3">
            <div class="page-header-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                <i class="bi bi-inbox-fill"></i>
            </div>
            <div>
                <h4 class="page-header-title">Antrean Tugas Baru Masuk</h4>
                <p class="page-header-desc">Verifikasi rincian target dan instruksi dari supervisor, lalu klik ACC untuk memulai pengerjaan.</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('operator.packaging-tasks.work') }}" class="btn btn-cyber-outline">
                <i class="bi bi-tools me-1"></i> Buka Pengerjaan Aktif ({{ $inProgressCount }})
            </a>
            <a href="{{ route('operator.dashboard') }}" class="btn btn-cyber-outline">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Metric Chips to prevent emptiness -->
    <div class="stat-chip-group">
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div>
                <div class="stat-chip-val" style="color: #fbbf24;">{{ $totalPending }} Tugas</div>
                <div class="stat-chip-title">Perlu Konfirmasi ACC</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8;">
                <i class="bi bi-gear-wide"></i>
            </div>
            <div>
                <div class="stat-chip-val">{{ $inProgressCount }} Pengerjaan</div>
                <div class="stat-chip-title">Sedang Berjalan di Stasiun</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <div class="stat-chip-val">SOP READY</div>
                <div class="stat-chip-title">Standar Proteksi VCI</div>
            </div>
        </div>
    </div>

    <!-- Task List Table Card -->
    <div class="cyber-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard-pulse text-accent"></i> Daftar Tugas Menunggu ACC
                </h5>
                <span class="text-muted small">Konfirmasi penerimaan penugasan untuk mencatat waktu mulai (timestamp ACC)</span>
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
                        <th width="190">Kode Tugas</th>
                        <th>Engine Valve</th>
                        <th>Target Qty</th>
                        <th>Target Kirim</th>
                        <th>Status</th>
                        <th class="text-end" width="130">Aksi</th>
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
                                {{ $task->quantity_target }} Unit
                            </td>
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
                            <td>
                                <span class="badge d-inline-flex align-items-center gap-1 py-1 px-2" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; font-size: 0.72rem; font-weight: 600;">
                                    <i class="bi bi-clock"></i> Menunggu ACC
                                </span>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('operator.packaging-tasks.accept', $task) }}" method="POST" onsubmit="return confirm('Konfirmasi penerimaan (ACC) tugas {{ $task->task_code }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-cyber py-1 px-3">
                                        <i class="bi bi-check2-circle me-1"></i> ACC Tugas
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-check-circle-fill fs-2 d-block mb-2 text-accent"></i>
                                Tidak ada tugas yang menunggu ACC saat ini.
                                <div class="mt-2 text-muted small">
                                    Semua tugas masuk telah Anda terima. Silakan lanjut ke modul pengerjaan aktif.
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('operator.packaging-tasks.work') }}" class="btn btn-sm btn-cyber">
                                        <i class="bi bi-tools me-1"></i> Buka Daftar Pengerjaan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr class="empty-filter-row" style="display: none;">
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
                            Tidak ada tugas yang sesuai dengan kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Menampilkan {{ $tasks->firstItem() ?? 0 }} - {{ $tasks->lastItem() ?? 0 }} dari {{ $tasks->total() }} tugas antrean</span>
            <div>
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection