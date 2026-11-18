@extends('layouts.app')
@section('title', 'Buat Tugas Packaging')

@section('content')
<div class="fade-in mx-auto" style="max-width: 680px;">
    <!-- Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('supervisor.packaging-tasks.index') }}" class="btn btn-sm btn-cyber-outline">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Penugasan
        </a>
        <span class="hud-telemetry-chip py-1 px-2">
            <span class="pulse-dot"></span> FORM PENUGASAN
        </span>
    </div>

    <div class="cyber-card p-4 p-md-5">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-15">
            <div class="page-header-icon" style="background: rgba(0, 245, 212, 0.12); color: var(--accent);">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div>
                <h4 class="fw-bold text-white mb-0">Alokasi Tugas Packaging Baru</h4>
                <p class="text-muted small mb-0">Tentukan part number valve, stasiun operator, target kuantitas, dan instruksi penanganan.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger glass-card border-0 mb-4">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($valves->isEmpty())
            <div class="alert alert-warning glass-card border-0 mb-4 d-flex align-items-center justify-content-between">
                <span>Belum ada data katalog Engine Valve.</span>
                <a href="{{ route('supervisor.engine-valves.create') }}" class="btn btn-sm btn-cyber">
                    + Tambah Valve Sekarang
                </a>
            </div>
        @endif

        <form method="POST" action="{{ route('supervisor.packaging-tasks.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label text-white small fw-bold">Pilih Engine Valve</label>
                <select name="engine_valve_id" class="form-select" required style="background: var(--surface-solid); border-color: var(--border); color: var(--text-bright);">
                    <option value="">-- Pilih Part Valve --</option>
                    @foreach ($valves as $valve)
                        <option value="{{ $valve->id }}" {{ old('engine_valve_id') == $valve->id ? 'selected' : '' }}>
                            [{{ $valve->code }}] {{ $valve->name }} ({{ $valve->type ?? 'Standard' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label text-white small fw-bold mb-0">Tugaskan ke Operator</label>
                    <a href="{{ route('supervisor.operators.create') }}" class="text-accent small text-decoration-none" style="font-size: 0.74rem;">
                        <i class="bi bi-plus-circle me-1"></i> Daftarkan Operator Baru
                    </a>
                </div>
                <select name="assigned_to" class="form-select" required style="background: var(--surface-solid); border-color: var(--border); color: var(--text-bright);">
                    <option value="">-- Pilih Operator Pengerjaan --</option>
                    @foreach ($operators as $op)
                        <option value="{{ $op->id }}" {{ old('assigned_to') == $op->id ? 'selected' : '' }}>
                            [{{ $op->npk ?? 'OP-' . $op->id }}] {{ $op->name }} • {{ $op->shift ?? 'Shift 1' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-white small fw-bold">Target Kuantitas (Unit / Pcs)</label>
                <div class="input-group">
                    <span class="input-group-text" style="background: var(--surface-solid); border-color: var(--border); color: var(--accent);">
                        <i class="bi bi-hash"></i>
                    </span>
                    <input type="number" name="quantity_target" class="form-control mono-font fw-bold text-white" min="1" value="{{ old('quantity_target', 100) }}" required style="background: var(--surface-solid); border-color: var(--border);">
                    <span class="input-group-text text-muted small" style="background: var(--surface-solid); border-color: var(--border);">
                        Pcs
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-white small fw-bold">Target Tanggal Pengiriman</label>
                <div class="input-group">
                    <span class="input-group-text" style="background: var(--surface-solid); border-color: var(--border); color: var(--accent);">
                        <i class="bi bi-calendar-event"></i>
                    </span>
                    <input type="date" name="target_delivery_date" class="form-control mono-font fw-bold text-white" min="{{ date('Y-m-d') }}" value="{{ old('target_delivery_date', date('Y-m-d', strtotime('+2 days'))) }}" required style="background: var(--surface-solid); border-color: var(--border);">
                </div>
                <small class="text-muted" style="font-size: 0.72rem;">Batas waktu penyelesaian pengerjaan packaging untuk jadwal dispatch armada.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-cyber">
                    <i class="bi bi-check2-circle me-1"></i> Terbitkan Penugasan
                </button>
                <a href="{{ route('supervisor.packaging-tasks.index') }}" class="btn btn-soft-action">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection