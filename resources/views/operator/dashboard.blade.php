@extends('layouts.app')
@section('title', 'Operator Workstation')

@section('content')
@php
    $assignedCount = $assignedCount ?? \App\Models\PackagingTask::where('assigned_to', auth()->id())->where('status', 'assigned')->count();
    $inProgressCount = $inProgressCount ?? \App\Models\PackagingTask::where('assigned_to', auth()->id())->whereIn('status', ['accepted', 'in_progress'])->count();
    $completedCount = $completedCount ?? \App\Models\PackagingTask::where('assigned_to', auth()->id())->where('status', 'completed')->count();
    $myTasks = $myTasks ?? \App\Models\PackagingTask::with('engineValve')->where('assigned_to', auth()->id())->latest()->take(10)->get();
    $priorityTask = $priorityTask ?? \App\Models\PackagingTask::with('engineValve')->where('assigned_to', auth()->id())->whereIn('status', ['accepted', 'in_progress'])->latest()->first();
    $totalTarget = $totalTarget ?? (int) \App\Models\PackagingTask::where('assigned_to', auth()->id())->sum('quantity_target');
    $totalPacked = $totalPacked ?? (int) \App\Models\PackagingTask::where('assigned_to', auth()->id())->sum('quantity_packed');
@endphp
<div class="fade-in">
    <!-- Top Workstation Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-white">Selamat bekerja, {{ auth()->user()->name }} 👋</h3>
            <p class="text-muted mb-0 small">Terminal kerja operator: Penerimaan tugas packaging valve, update progres pengerjaan, dan pencetakan riwayat.</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('operator.packaging-tasks.index') }}" class="btn btn-cyber">
                <i class="bi bi-inbox-fill me-1"></i> Tugas Menunggu ACC
                @if($assignedCount > 0)
                    <span class="badge bg-danger ms-1 px-2 py-1" style="font-size: 0.72rem;">{{ $assignedCount }} Baru</span>
                @endif
            </a>
            <a href="{{ route('operator.packaging-tasks.work') }}" class="btn btn-cyber-outline">
                <i class="bi bi-tools me-1"></i> Daftar Pengerjaan
            </a>
        </div>
    </div>

    <!-- Alert for Pending Tasks if any -->
    @if($assignedCount > 0)
        <div class="cyber-card p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="border-left: 4px solid var(--status-warning); background: rgba(245, 158, 11, 0.08);">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(245, 158, 11, 0.2); color: #f59e0b;">
                    <i class="bi bi-bell-fill fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold text-white small">Ada {{ $assignedCount }} Penugasan Baru dari Supervisor</div>
                    <div class="text-muted small">Harap lakukan konfirmasi penerimaan (ACC) sebelum memulai pengerjaan fisik packaging.</div>
                </div>
            </div>
            <a href="{{ route('operator.packaging-tasks.index') }}" class="btn btn-sm btn-cyber py-1 px-3">
                Buka & ACC Sekarang <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    @endif

    <!-- 3 Interactive Operator KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Menunggu ACC -->
        <div class="col-md-4">
            <div class="cyber-stat-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="stat-label">MENUNGGU ACC</span>
                    <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.12); color: #fbbf24;">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #fbbf24;">{{ $assignedCount }}</div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary border-opacity-10">
                    <span class="text-muted small">Tugas Masuk Baru</span>
                    <a href="{{ route('operator.packaging-tasks.index') }}" class="text-accent small text-decoration-none fw-semibold">
                        Buka Tugas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI 2: Sedang Dikerjakan -->
        <div class="col-md-4">
            <div class="cyber-stat-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="stat-label">SEDANG DIKERJAKAN</span>
                    <div class="stat-icon-wrapper" style="background: rgba(14, 165, 233, 0.12); color: #38bdf8;">
                        <i class="bi bi-gear-wide"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #38bdf8;">{{ $inProgressCount }}</div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary border-opacity-10">
                    <span class="text-muted small">Update Progres Packaging</span>
                    <a href="{{ route('operator.packaging-tasks.work') }}" class="text-accent small text-decoration-none fw-semibold">
                        Input Hasil <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI 3: Selesai -->
        <div class="col-md-4">
            <div class="cyber-stat-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="stat-label">SELESAI TERPACKING</span>
                    <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.12); color: #34d399;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #34d399;">{{ $completedCount }}</div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary border-opacity-10">
                    <span class="text-muted small">Siap Kirim</span>
                    <a href="{{ route('operator.packaging-tasks.history') }}" class="text-accent small text-decoration-none fw-semibold">
                        Riwayat & PDF <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Workstation Focus Card (If Active Task Exists) -->
    @if($priorityTask)
        @php
            $priorityPercent = $priorityTask->quantity_target > 0
                ? min(100, round(($priorityTask->quantity_packed / $priorityTask->quantity_target) * 100))
                : 0;
        @endphp
        <div class="priority-focus-card mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                <div>
                    <span class="badge py-1 px-2 mb-2" style="background: rgba(0, 245, 212, 0.2); color: var(--accent); font-family: var(--font-mono); font-size: 0.72rem; letter-spacing: 0.5px;">
                        <i class="bi bi-lightning-charge-fill"></i> TUGAS PRIORITAS AKTIF
                    </span>
                    <h4 class="fw-bold text-white mb-1">{{ $priorityTask->engineValve->name }}</h4>
                    <div class="d-flex align-items-center gap-2">
                        <span class="mono-font text-accent small fw-bold">{{ $priorityTask->task_code }}</span>
                        <span class="text-muted">•</span>
                        <span class="mono-font text-muted small">Part: {{ $priorityTask->engineValve->code }}</span>
                        @if($priorityTask->target_delivery_date)
                            <span class="text-muted">•</span>
                            <span class="badge" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8; font-size: 0.7rem;">
                                <i class="bi bi-calendar2-check me-1"></i> Target: {{ \Carbon\Carbon::parse($priorityTask->target_delivery_date)->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                </div>

                <a href="{{ route('operator.packaging-tasks.edit', $priorityTask) }}" class="btn btn-cyber py-2 px-4 shadow">
                    <i class="bi bi-pencil-square me-1"></i> Lanjutkan Pengerjaan Sekarang
                </a>
            </div>

            <!-- Progress Details -->
            <div class="p-3 rounded-3 mb-2" style="background: rgba(0, 0, 0, 0.25); border: 1px solid var(--border);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-muted">Progres Hasil Packing Terkini:</span>
                    <span class="mono-font fw-bold text-white small">
                        <span class="text-accent">{{ $priorityTask->quantity_packed }}</span> / {{ $priorityTask->quantity_target }} Unit ({{ $priorityPercent }}%)
                    </span>
                </div>
                <div class="progress-modern">
                    <div class="progress-bar-modern" style="width: {{ $priorityPercent }}%; height: 8px;"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Grid: Task Queue -->
    <div class="row g-4">
        <!-- Operator Task Queue (full width) -->
        <div class="col-12">
            <div class="cyber-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                            <i class="bi bi-list-task text-accent"></i> Antrean Tugas Saya
                        </h5>
                        <span class="text-muted small">Tugas packaging valve yang ditugaskan kepada Anda</span>
                    </div>

                    <a href="{{ route('operator.packaging-tasks.work') }}" class="btn btn-sm btn-soft-action">
                        Semua Pengerjaan <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>

                <!-- Filter Tabs & Real-Time Search -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="filter-tabs-wrapper">
                        <button type="button" class="filter-tab-btn active" data-filter="all">Semua</button>
                        <button type="button" class="filter-tab-btn" data-filter="assigned">Menunggu ACC</button>
                        <button type="button" class="filter-tab-btn" data-filter="in_progress">Dikerjakan</button>
                        <button type="button" class="filter-tab-btn" data-filter="completed">Selesai</button>
                    </div>

                    <div class="search-box-modern flex-grow-1 flex-sm-grow-0" style="min-width: 200px;">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control form-control-sm table-search-input" placeholder="Cari tugas / valve...">
                    </div>
                </div>

                <div class="table-responsive" data-filterable-table>
                    <table class="table table-cyber align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Engine Valve</th>
                                <th>Target</th>
                                <th>Hasil</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($myTasks as $task)
                                @php
                                    $filterStatus = in_array($task->status, ['accepted', 'in_progress']) ? 'in_progress' : $task->status;

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
                                            <button type="button" class="copy-code-btn" onclick="copyTaskCode('{{ $task->task_code }}', this)" title="Salin Kode">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-white small">{{ $task->engineValve->name }}</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted mono-font" style="font-size: 0.72rem;">{{ $task->engineValve->code }}</small>
                                            @if($task->target_delivery_date)
                                                <small class="text-info mono-font" style="font-size: 0.7rem;">
                                                    • <i class="bi bi-calendar2-check"></i> {{ \Carbon\Carbon::parse($task->target_delivery_date)->format('d/m') }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="mono-font small text-white">{{ $task->quantity_target }}</td>
                                    <td class="mono-font small text-accent fw-bold">{{ $task->quantity_packed }}</td>
                                    <td>
                                        <span class="badge d-inline-flex align-items-center gap-1 py-1 px-2" style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }}; font-size: 0.72rem; font-weight: 600;">
                                            <i class="bi {{ $statusBadge['icon'] }}"></i> {{ $statusBadge['label'] }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if($task->status === 'assigned')
                                            <a href="{{ route('operator.packaging-tasks.index') }}" class="btn btn-sm btn-cyber py-1 px-2" title="Konfirmasi ACC Tugas">
                                                <i class="bi bi-check-lg"></i> ACC
                                            </a>
                                        @elseif(in_array($task->status, ['accepted', 'in_progress']))
                                            <a href="{{ route('operator.packaging-tasks.edit', $task) }}" class="btn btn-sm btn-soft-action py-1 px-2" title="Input Progres">
                                                <i class="bi bi-pencil-square"></i> Input
                                            </a>
                                        @else
                                            <a href="{{ route('operator.packaging-tasks.pdf', $task) }}" class="btn btn-sm btn-soft-action py-1 px-2" title="Download Bukti PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-check2-all fs-3 d-block mb-2 text-accent"></i>
                                        Tidak ada tugas yang menunggu atau sedang berjalan.
                                    </td>
                                </tr>
                            @endforelse

                            <tr class="empty-filter-row" style="display: none;">
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
                                    Tidak ada tugas yang cocok dengan filter atau kata kunci pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection