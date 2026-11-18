@extends('layouts.app')
@section('title', 'Daftar Penugasan Kerja')

@section('content')
<div class="fade-in">
    <!-- Header Banner -->
    <div class="page-header-box">
        <div class="d-flex align-items-center gap-3">
            <div class="page-header-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div>
                <h4 class="page-header-title">Penugasan & Monitoring Packaging</h4>
                <p class="page-header-desc">Kelola dan pantau seluruh instruksi kerja pengemasan valve PT Federal Nittan Industries.</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('supervisor.packaging-tasks.create') }}" class="btn btn-cyber">
                <i class="bi bi-plus-circle-fill me-1"></i> Buat Penugasan Baru
            </a>
            <a href="{{ route('supervisor.packaging-tasks.monitoring') }}" class="btn btn-cyber-outline">
                <i class="bi bi-broadcast me-1"></i> Live Monitoring
            </a>
        </div>
    </div>

    <!-- Metric Chips -->
    <div class="stat-chip-group">
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8;">
                <i class="bi bi-stack"></i>
            </div>
            <div>
                <div class="stat-chip-val">{{ $totalCount }}</div>
                <div class="stat-chip-title">Total Penugasan</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(148, 163, 184, 0.15); color: #94a3b8;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div class="stat-chip-val" style="color: #94a3b8;">{{ $assignedCount }}</div>
                <div class="stat-chip-title">Menunggu ACC</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <div>
                <div class="stat-chip-val" style="color: #fbbf24;">{{ $inProgressCount }}</div>
                <div class="stat-chip-title">Sedang Diproses</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-chip-val" style="color: #34d399;">{{ $completedCount }}</div>
                <div class="stat-chip-title">Selesai Siap Kirim</div>
            </div>
        </div>
    </div>

    <!-- Task Table Card Hub -->
    <div class="cyber-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-table text-accent"></i> Daftar Penugasan Kerja
                </h5>
                <span class="small" style="color: #94a3b8;">Filter berdasarkan status atau cari kode tugas, valve, maupun operator</span>
            </div>

            <!-- Real-Time Search Bar -->
            <div class="search-box-modern flex-grow-1 flex-sm-grow-0" style="min-width: 260px;">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control form-control-sm table-search-input" placeholder="Cari kode / valve / operator...">
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs-wrapper mb-3">
            <button type="button" class="filter-tab-btn active" data-filter="all">Semua ({{ $totalCount }})</button>
            <button type="button" class="filter-tab-btn" data-filter="assigned">Menunggu ACC ({{ $assignedCount }})</button>
            <button type="button" class="filter-tab-btn" data-filter="in_progress">Diproses ({{ $inProgressCount }})</button>
            <button type="button" class="filter-tab-btn" data-filter="completed">Selesai ({{ $completedCount }})</button>
        </div>

        <div class="table-responsive" data-filterable-table>
            <table class="table table-cyber align-middle">
                <thead>
                    <tr>
                        <th width="180">Kode Tugas</th>
                        <th>Engine Valve</th>
                        <th>Operator</th>
                        <th>Target Qty</th>
                        <th>Hasil</th>
                        <th>Target Kirim</th>
                        <th>Progres</th>
                        <th>Status</th>
                        <th class="text-end" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        @php
                            $filterStatus = in_array($task->status, ['accepted', 'in_progress']) ? 'in_progress' : $task->status;
                            $percent = $task->quantity_target > 0
                                ? min(100, round(($task->quantity_packed / $task->quantity_target) * 100))
                                : 0;

                            $statusBadge = match($task->status) {
                                'assigned' => ['bg' => 'rgba(148, 163, 184, 0.15)', 'color' => '#94a3b8', 'label' => 'Menunggu ACC', 'icon' => 'bi-clock'],
                                'accepted' => ['bg' => 'rgba(14, 165, 233, 0.15)', 'color' => '#38bdf8', 'label' => 'Diterima', 'icon' => 'bi-check2'],
                                'in_progress' => ['bg' => 'rgba(245, 158, 11, 0.15)', 'color' => '#fbbf24', 'label' => 'Dikerjakan', 'icon' => 'bi-gear-wide'],
                                'completed' => ['bg' => 'rgba(16, 185, 129, 0.15)', 'color' => '#34d399', 'label' => 'Selesai', 'icon' => 'bi-check-all'],
                                default => ['bg' => 'rgba(148, 163, 184, 0.15)', 'color' => '#94a3b8', 'label' => $task->status, 'icon' => 'bi-dot'],
                            };
                        @endphp
                        <tr data-status="{{ $filterStatus }}">
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="mono-font fw-bold text-accent small">{{ $task->task_code }}</span>
                                    <button type="button" class="copy-code-btn" onclick="copyTaskCode('{{ $task->task_code }}', this)" title="Salin Kode Tugas">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-white small">{{ $task->engineValve->name }}</div>
                                <span class="mono-font" style="font-size: 0.72rem; color: #94a3b8;">{{ $task->engineValve->code }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 26px; height: 26px; font-size: 0.72rem; background: rgba(255,255,255,0.12); font-weight: 600;">
                                        {{ strtoupper(substr($task->operator->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="small fw-semibold text-white">{{ $task->operator->name }}</div>
                                        <span class="mono-font" style="font-size: 0.68rem; color: #94a3b8;">{{ $task->operator->npk ?? 'OP' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="mono-font small fw-semibold text-white">{{ $task->quantity_target }}</td>
                            <td class="mono-font small text-accent fw-bold">{{ $task->quantity_packed }}</td>
                            <td>
                                <span class="badge" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8; font-family: var(--font-mono); font-size: 0.72rem; border: 1px solid rgba(14, 165, 233, 0.25);">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $task->target_delivery_date ? $task->target_delivery_date->format('d/m/Y') : '-' }}
                                </span>
                            </td>
                            <td style="min-width: 100px;">
                                <div class="progress-modern mb-1">
                                    <div class="progress-bar-modern" style="width: {{ $percent }}%; height: 6px;"></div>
                                </div>
                                <span class="mono-font" style="font-size: 0.7rem; color: #94a3b8;">{{ $percent }}%</span>
                            </td>
                            <td>
                                <span class="badge d-inline-flex align-items-center gap-1 py-1 px-2" style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }}; font-size: 0.72rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.08);">
                                    <i class="bi {{ $statusBadge['icon'] }}"></i> {{ $statusBadge['label'] }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('supervisor.packaging-tasks.show', $task) }}" class="btn btn-sm btn-soft-action py-1 px-2" title="Detail Penugasan">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($task->status === 'assigned')
                                        <form action="{{ route('supervisor.packaging-tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan dan menghapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-action py-1 px-2 text-danger" title="Hapus Tugas">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                Belum ada penugasan packaging yang dibuat.
                                <div class="mt-2">
                                    <a href="{{ route('supervisor.packaging-tasks.create') }}" class="btn btn-sm btn-cyber">
                                        <i class="bi bi-plus-circle me-1"></i> Buat Penugasan Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr class="empty-filter-row" style="display: none;">
                        <td colspan="9" class="text-center py-4" style="color: #94a3b8;">
                            <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
                            Tidak ada penugasan yang sesuai dengan filter atau kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="small" style="color: #94a3b8;">Menampilkan data {{ $tasks->firstItem() ?? 0 }} - {{ $tasks->lastItem() ?? 0 }} dari {{ $tasks->total() }} penugasan</span>
            <div>
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection