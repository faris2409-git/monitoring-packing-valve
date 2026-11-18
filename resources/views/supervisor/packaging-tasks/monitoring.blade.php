@extends('layouts.app')
@section('title', 'Info Pengerjaan Operator')

@section('content')
@php
    $totalTasks = \App\Models\PackagingTask::where('created_by', auth()->id())->count();
    $activeCount = \App\Models\PackagingTask::where('created_by', auth()->id())->whereIn('status', ['accepted', 'in_progress'])->count();
    $totalTarget = (int) \App\Models\PackagingTask::where('created_by', auth()->id())->sum('quantity_target');
    $totalPacked = (int) \App\Models\PackagingTask::where('created_by', auth()->id())->sum('quantity_packed');
    $overallPercent = $totalTarget > 0 ? round(($totalPacked / $totalTarget) * 100) : 0;
@endphp

<div class="fade-in">
    <!-- Header Banner -->
    <div class="page-header-box">
        <div class="d-flex align-items-center gap-3">
            <div class="page-header-icon" style="background: rgba(0, 245, 212, 0.12); color: var(--accent);">
                <i class="bi bi-broadcast"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="page-header-title">Live Telemetri Pengerjaan Operator</h4>
                    <span class="hud-telemetry-chip py-1 px-2">
                        <span class="pulse-dot"></span> FEED LIVE
                    </span>
                </div>
                <p class="page-header-desc">Pemantauan progres aktual fisik packaging per operator di stasiun kerja dan catatan kendala.</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('supervisor.packaging-tasks.create') }}" class="btn btn-cyber">
                <i class="bi bi-plus-circle-fill me-1"></i> Buat Tugas Baru
            </a>
            <a href="{{ route('supervisor.dashboard') }}" class="btn btn-cyber-outline">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Metric Chips to prevent emptiness -->
    <div class="stat-chip-group">
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                <i class="bi bi-tools"></i>
            </div>
            <div>
                <div class="stat-chip-val" style="color: #fbbf24;">{{ $activeCount }} Stasiun</div>
                <div class="stat-chip-title">Tugas Sedang Diproses</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8;">
                <i class="bi bi-pie-chart"></i>
            </div>
            <div>
                <div class="stat-chip-val">{{ $totalPacked }} <span class="text-muted" style="font-size: 0.8rem;">/ {{ $totalTarget }}</span></div>
                <div class="stat-chip-title">Total Unit Terpacking ({{ $overallPercent }}%)</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                <i class="bi bi-activity"></i>
            </div>
            <div>
                <div class="stat-chip-val" style="color: #34d399;">100% RUNNING</div>
                <div class="stat-chip-title">Lini Packaging Shift 1</div>
            </div>
        </div>
    </div>

    <!-- Live Monitoring Table Card -->
    <div class="cyber-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-display text-accent"></i> Monitor Stasiun Pengerjaan
                </h5>
                <span class="text-muted small">Update berkala langsung dari terminal workstation operator</span>
            </div>

            <!-- Filter By Status & Search -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" class="d-inline-flex gap-2 m-0">
                    <select name="status" class="form-select form-select-sm" style="min-width: 160px; background: var(--surface-solid); color: var(--text-bright); border-color: var(--border);" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Menunggu ACC</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima Operator</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </form>

                <div class="search-box-modern" style="min-width: 220px;">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control form-control-sm table-search-input" placeholder="Cari kode / valve / operator...">
                </div>
            </div>
        </div>

        <div class="table-responsive" data-filterable-table>
            <table class="table table-cyber align-middle">
                <thead>
                    <tr>
                        <th width="180">Kode Tugas</th>
                        <th>Engine Valve</th>
                        <th>Operator Bertugas</th>
                        <th>Target Qty</th>
                        <th>Hasil Terpacking</th>
                        <th>Progres</th>
                        <th>Status</th>
                        <th>Catatan Operator</th>
                        <th class="text-end" width="90">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        @php
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
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 24px; height: 24px; font-size: 0.7rem; background: rgba(255,255,255,0.1);">
                                        {{ strtoupper(substr($task->operator->name, 0, 1)) }}
                                    </div>
                                    <span class="small">{{ $task->operator->name }}</span>
                                </div>
                            </td>
                            <td class="mono-font small text-white">{{ $task->quantity_target }}</td>
                            <td class="mono-font small text-accent fw-bold">{{ $task->quantity_packed }}</td>
                            <td style="min-width: 120px;">
                                <div class="progress-modern mb-1">
                                    <div class="progress-bar-modern" style="width: {{ $percent }}%; height: 6px;"></div>
                                </div>
                                <span class="mono-font text-muted" style="font-size: 0.7rem;">{{ $percent }}% Selesai</span>
                            </td>
                            <td>
                                <span class="badge d-inline-flex align-items-center gap-1 py-1 px-2" style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }}; font-size: 0.72rem; font-weight: 600;">
                                    <i class="bi {{ $statusBadge['icon'] }}"></i> {{ $statusBadge['label'] }}
                                </span>
                            </td>
                            <td>
                                @if($task->notes)
                                    <span class="small text-white" title="{{ $task->notes }}">
                                        <i class="bi bi-chat-text text-accent me-1"></i>
                                        {{ Str::limit($task->notes, 30) }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('supervisor.packaging-tasks.show', $task) }}" class="btn btn-sm btn-soft-action py-1 px-2" title="Detail Penugasan">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-broadcast fs-2 d-block mb-2 text-muted"></i>
                                Tidak ada aktivitas pengerjaan yang cocok dengan filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse

                    <tr class="empty-filter-row" style="display: none;">
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
                            Tidak ada data pengerjaan yang sesuai dengan pencarian Anda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Menampilkan data {{ $tasks->firstItem() ?? 0 }} - {{ $tasks->lastItem() ?? 0 }} dari {{ $tasks->total() }} tugas</span>
            <div>
                {{ $tasks->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection