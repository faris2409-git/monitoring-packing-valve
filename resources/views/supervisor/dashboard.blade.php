@extends('layouts.app')
@section('title', 'Command Center - Supervisor')

@section('content')
@php
    $totalValves = $totalValves ?? \App\Models\EngineValve::count();
    $assignedTasksCount = $assignedTasksCount ?? \App\Models\PackagingTask::where('status', 'assigned')->count();
    $inProgressTasksCount = $inProgressTasksCount ?? \App\Models\PackagingTask::whereIn('status', ['accepted', 'in_progress'])->count();
    $activeTasks = $activeTasks ?? ($assignedTasksCount + $inProgressTasksCount);
    $completedTasks = $completedTasks ?? \App\Models\PackagingTask::where('status', 'completed')->count();
    $totalShipments = $totalShipments ?? \App\Models\Shipment::count();
    $recentTasks = $recentTasks ?? \App\Models\PackagingTask::with(['engineValve', 'operator'])->latest()->take(10)->get();
@endphp
<div class="fade-in">
    <!-- Top Command Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-white">Selamat datang, {{ auth()->user()->name }} 👋</h3>
            <p class="text-muted mb-0 small">Pemantauan lini packaging engine valve, alokasi penugasan operator, dan penerbitan surat jalan.</p>
        </div>

        <!-- Quick Action Bar -->
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('supervisor.packaging-tasks.create') }}" class="btn btn-cyber">
                <i class="bi bi-plus-circle-fill me-1"></i> Buat Penugasan
            </a>
            <a href="{{ route('supervisor.engine-valves.create') }}" class="btn btn-cyber-outline">
                <i class="bi bi-gear-wide me-1"></i> Tambah Valve
            </a>
            <a href="{{ route('supervisor.shipments.create') }}" class="btn btn-cyber-outline">
                <i class="bi bi-truck me-1"></i> Buat Surat Jalan
            </a>
        </div>
    </div>

    <!-- 4 Interactive KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total Valves -->
        <div class="col-sm-6 col-xl-3">
            <div class="cyber-stat-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="stat-label">KATALOG VALVE</span>
                    <div class="stat-icon-wrapper" style="background: rgba(14, 165, 233, 0.12); color: #38bdf8;">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalValves }}</div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary border-opacity-10">
                    <span class="text-muted small">Tipe Terdaftar</span>
                    <a href="{{ route('supervisor.engine-valves.index') }}" class="text-accent small text-decoration-none fw-semibold">
                        Lihat Data <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI 2: Active Tasks -->
        <div class="col-sm-6 col-xl-3">
            <div class="cyber-stat-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="stat-label">TUGAS AKTIF</span>
                    <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.12); color: #fbbf24;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #fbbf24;">{{ $activeTasks }}</div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary border-opacity-10">
                    <span class="text-muted small">{{ $assignedTasksCount }} Antre • {{ $inProgressTasksCount }} Proses</span>
                    <a href="{{ route('supervisor.packaging-tasks.monitoring') }}" class="text-accent small text-decoration-none fw-semibold">
                        Pantau Live <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI 3: Completed Tasks -->
        <div class="col-sm-6 col-xl-3">
            <div class="cyber-stat-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="stat-label">SELESAI TERPACKING</span>
                    <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.12); color: #34d399;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #34d399;">{{ $completedTasks }}</div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary border-opacity-10">
                    <span class="text-muted small">Siap Distribusi</span>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.18); color: #34d399; font-size: 0.68rem;">QC PASSED</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Shipments -->
        <div class="col-sm-6 col-xl-3">
            <div class="cyber-stat-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="stat-label">SURAT JALAN</span>
                    <div class="stat-icon-wrapper" style="background: rgba(0, 245, 212, 0.12); color: var(--accent);">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: var(--accent);">{{ $totalShipments }}</div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary border-opacity-10">
                    <span class="text-muted small">Dokumen Diterbitkan</span>
                    <a href="{{ route('supervisor.shipments.index') }}" class="text-accent small text-decoration-none fw-semibold">
                        Buka Arsip <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Recent Tasks Hub & Factory Guidelines -->
    <div class="row g-4">
        <!-- Packaging Tasks Interactive Table Hub -->
        <div class="col-12">
            <div class="cyber-card p-4">
                <!-- Section Header with Controls -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                            <i class="bi bi-activity text-accent"></i> Aktivitas Packaging
                        </h5>
                        <span class="text-muted small">Daftar penugasan terkini dengan filter instan dan pencarian langsung</span>
                    </div>

                    <a href="{{ route('supervisor.packaging-tasks.monitoring') }}" class="btn btn-sm btn-soft-action">
                        Lihat Semua Monitor <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>

                <!-- Interactive Filter Tabs & Real-Time Search Bar -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="filter-tabs-wrapper">
                        <button type="button" class="filter-tab-btn active" data-filter="all">Semua</button>
                        <button type="button" class="filter-tab-btn" data-filter="assigned">Menunggu ACC</button>
                        <button type="button" class="filter-tab-btn" data-filter="in_progress">Diproses</button>
                        <button type="button" class="filter-tab-btn" data-filter="completed">Selesai</button>
                    </div>

                    <div class="search-box-modern flex-grow-1 flex-sm-grow-0" style="min-width: 220px;">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control form-control-sm table-search-input" placeholder="Cari kode / valve / operator...">
                    </div>
                </div>

                <!-- Filterable Table -->
                <div class="table-responsive" data-filterable-table>
                    <table class="table table-cyber align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Engine Valve</th>
                                <th>Operator</th>
                                <th>Target / Hasil</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTasks as $task)
                                @php
                                    $percent = $task->quantity_target > 0
                                        ? min(100, round(($task->quantity_packed / $task->quantity_target) * 100))
                                        : 0;
                                    
                                    // Normalize status for filtering
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
                                        <small class="text-muted mono-font" style="font-size: 0.72rem;">{{ $task->engineValve->code }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 24px; height: 24px; font-size: 0.7rem; background: rgba(255,255,255,0.1);">
                                                {{ strtoupper(substr($task->operator->name, 0, 1)) }}
                                            </div>
                                            <span class="small">{{ $task->operator->name }}</span>
                                        </div>
                                    </td>
                                    <td class="mono-font small">
                                        <span class="text-white fw-bold">{{ $task->quantity_packed }}</span>
                                        <span class="text-muted">/ {{ $task->quantity_target }}</span>
                                    </td>
                                    <td style="min-width: 110px;">
                                        <div class="progress-modern mb-1">
                                            <div class="progress-bar-modern" style="width: {{ $percent }}%; height: 6px;"></div>
                                        </div>
                                        <span class="mono-font text-muted" style="font-size: 0.7rem;">{{ $percent }}%</span>
                                    </td>
                                    <td>
                                        <span class="badge d-inline-flex align-items-center gap-1 py-1 px-2" style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }}; font-size: 0.72rem; font-weight: 600;">
                                            <i class="bi {{ $statusBadge['icon'] }}"></i> {{ $statusBadge['label'] }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('supervisor.packaging-tasks.show', $task) }}" class="btn btn-sm btn-soft-action py-1 px-2" title="Detail Tugas">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                        Belum ada data tugas packaging yang dibuat.
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Empty filter placeholder row -->
                            <tr class="empty-filter-row" style="display: none;">
                                <td colspan="7" class="text-center text-muted py-4">
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