@extends('layouts.app')
@section('title', 'Detail Tugas ' . $packagingTask->task_code)

@section('content')
@php
    $percent = $packagingTask->quantity_target > 0
        ? min(100, round(($packagingTask->quantity_packed / $packagingTask->quantity_target) * 100))
        : 0;

    $statusBadge = match($packagingTask->status) {
        'assigned' => ['bg' => 'rgba(148, 163, 184, 0.15)', 'color' => '#94a3b8', 'label' => 'Menunggu ACC Operator', 'icon' => 'bi-clock'],
        'accepted' => ['bg' => 'rgba(14, 165, 233, 0.15)', 'color' => '#38bdf8', 'label' => 'Diterima Operator', 'icon' => 'bi-check2'],
        'in_progress' => ['bg' => 'rgba(245, 158, 11, 0.15)', 'color' => '#fbbf24', 'label' => 'Sedang Dikerjakan', 'icon' => 'bi-gear-wide'],
        'completed' => ['bg' => 'rgba(16, 185, 129, 0.15)', 'color' => '#34d399', 'label' => 'Selesai Terpacking', 'icon' => 'bi-check-all'],
        default => ['bg' => 'rgba(148, 163, 184, 0.15)', 'color' => '#94a3b8', 'label' => $packagingTask->status, 'icon' => 'bi-dot'],
    };
@endphp

<div class="fade-in mx-auto" style="max-width: 820px;">
    <!-- Breadcrumb & Top Action -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('supervisor.packaging-tasks.index') }}" class="btn btn-sm btn-cyber-outline">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Penugasan
        </a>
        <span class="badge py-1 px-3" style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }}; font-size: 0.78rem; font-weight: 600;">
            <i class="bi {{ $statusBadge['icon'] }} me-1"></i> {{ $statusBadge['label'] }}
        </span>
    </div>

    <!-- Main Detail Card -->
    <div class="cyber-card p-4 p-md-5 mb-4">
        <!-- Title & Code Header -->
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 pb-3 mb-4 border-bottom border-secondary border-opacity-15">
            <div>
                <div class="hud-telemetry-chip mb-2">
                    <span class="pulse-dot"></span>
                    <span>TASK TELEMETRY DETAIL</span>
                </div>
                <h3 class="fw-bold text-white mb-1">{{ $packagingTask->engineValve->name }}</h3>
                <div class="d-flex align-items-center gap-2">
                    <span class="mono-font text-accent fw-bold">{{ $packagingTask->task_code }}</span>
                    <span class="text-muted">•</span>
                    <span class="mono-font text-muted small">Part Number: {{ $packagingTask->engineValve->code }}</span>
                </div>
            </div>

            <div class="text-end">
                <div class="mono-font text-muted small">Dibuat Oleh</div>
                <div class="fw-bold text-white">{{ $packagingTask->supervisor->name ?? auth()->user()->name }}</div>
                <small class="text-muted mono-font">{{ $packagingTask->created_at->format('d M Y H:i') }} WIB</small>
            </div>
        </div>

        <!-- Progress Widget Box -->
        <div class="p-3 rounded-3 mb-4" style="background: var(--surface-subtle); border: 1px solid var(--border);">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Pencapaian Packaging Terkini:</span>
                <span class="mono-font fw-bold text-white">
                    <span class="text-accent">{{ $packagingTask->quantity_packed }}</span> / {{ $packagingTask->quantity_target }} Unit ({{ $percent }}%)
                </span>
            </div>
            <div class="progress-modern mb-2">
                <div class="progress-bar-modern" style="width: {{ $percent }}%; height: 8px;"></div>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <div class="p-3 rounded-3 h-100" style="background: var(--surface-solid); border: 1px solid var(--border-subtle);">
                    <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Operator Bertugas</div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem; background: rgba(0, 245, 212, 0.15); color: var(--accent) !important;">
                            {{ strtoupper(substr($packagingTask->operator->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-white">{{ $packagingTask->operator->name }}</div>
                            <small class="text-muted mono-font">
                                @if($packagingTask->operator->npk)
                                    <span class="text-accent fw-semibold">NPK: {{ $packagingTask->operator->npk }}</span> •
                                @endif
                                <span>{{ $packagingTask->operator->shift ?? 'Shift 1' }}</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="p-3 rounded-3 h-100" style="background: var(--surface-solid); border: 1px solid var(--border-subtle);">
                    <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Spesifikasi Valve</div>
                    <div class="fw-bold text-white">{{ $packagingTask->engineValve->type ?? 'Standar OEM' }}</div>
                    <small class="text-muted">{{ $packagingTask->engineValve->spec ?? 'Standard JIS Packing Tolerance' }}</small>
                </div>
            </div>

            <div class="col-12">
                <div class="p-3 rounded-3" style="background: rgba(14, 165, 233, 0.08); border: 1px solid rgba(14, 165, 233, 0.25);">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <div class="text-accent small text-uppercase fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                                <i class="bi bi-calendar2-check-fill me-1"></i> Target Tanggal Pengiriman
                            </div>
                            <div class="fs-5 fw-bold text-white mono-font">
                                {{ $packagingTask->target_delivery_date ? \Carbon\Carbon::parse($packagingTask->target_delivery_date)->translatedFormat('l, d F Y') : '-' }}
                            </div>
                        </div>
                        @if($packagingTask->target_delivery_date)
                            @php
                                $targetDate = \Carbon\Carbon::parse($packagingTask->target_delivery_date)->startOfDay();
                                $today = \Carbon\Carbon::now()->startOfDay();
                                $diff = $today->diffInDays($targetDate, false);
                            @endphp
                            <div>
                                @if($packagingTask->status === 'completed')
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 0.8rem;">
                                        <i class="bi bi-check-circle me-1"></i> Selesai
                                    </span>
                                @elseif($diff < 0)
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #f87171; font-size: 0.8rem;">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Terlambat {{ abs($diff) }} Hari
                                    </span>
                                @elseif($diff === 0)
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; font-size: 0.8rem;">
                                        <i class="bi bi-clock-fill me-1"></i> Hari Ini
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(56, 189, 248, 0.2); color: #38bdf8; font-size: 0.8rem;">
                                        <i class="bi bi-hourglass-split me-1"></i> Sisa {{ $diff }} Hari
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($packagingTask->instruction)
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background: var(--surface-solid); border: 1px solid var(--border-subtle);">
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Instruksi Tambahan</div>
                        <div class="text-white small">{{ $packagingTask->instruction }}</div>
                    </div>
                </div>
            @endif

            @if($packagingTask->notes)
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background: rgba(14, 165, 233, 0.05); border: 1px solid rgba(14, 165, 233, 0.2);">
                        <div class="text-accent small text-uppercase fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Catatan Realtime dari Operator</div>
                        <div class="text-white small">{{ $packagingTask->notes }}</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Timestamps Grid -->
        <div class="row g-2 pt-3 border-top border-secondary border-opacity-15 text-muted small mono-font">
            <div class="col-sm-4">
                <span>Dibuat:</span> <strong class="text-white">{{ $packagingTask->created_at->format('d/m/Y H:i') }}</strong>
            </div>
            <div class="col-sm-4">
                <span>Diterima ACC:</span> <strong class="text-white">{{ $packagingTask->accepted_at ? \Carbon\Carbon::parse($packagingTask->accepted_at)->format('d/m/Y H:i') : '-' }}</strong>
            </div>
            <div class="col-sm-4">
                <span>Selesai:</span> <strong class="text-white">{{ $packagingTask->completed_at ? \Carbon\Carbon::parse($packagingTask->completed_at)->format('d/m/Y H:i') : '-' }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection