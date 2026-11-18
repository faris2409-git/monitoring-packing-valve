@extends('layouts.app')
@section('title', 'Edit Engine Valve: ' . $engineValve->code)

@section('content')
<div class="fade-in mx-auto" style="max-width: 680px;">
    <!-- Breadcrumb / Back button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('supervisor.engine-valves.index') }}" class="btn btn-sm btn-cyber-outline">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Engine Valve
        </a>
        <span class="hud-telemetry-chip py-1 px-2">
            <span class="pulse-dot"></span> EDIT VALVE
        </span>
    </div>

    <div class="cyber-card p-4 p-md-5">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-15">
            <div class="page-header-icon" style="background: rgba(0, 245, 212, 0.12); color: var(--accent);">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div>
                <span class="mono-font text-accent fw-bold small">{{ $engineValve->code }}</span>
                <h4 class="fw-bold text-white mb-0">Perbarui Data Engine Valve</h4>
                <p class="text-muted small mb-0">Ubah nama merk (Yamaha, AHM, Suzuki), kode barang, atau tipe katup.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger glass-card border-0 mb-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    <span class="fw-bold text-white small">Terjadi kesalahan pengisian form:</span>
                </div>
                <ul class="mb-0 small ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('supervisor.engine-valves.update', $engineValve) }}">
            @csrf
            @method('PUT')

            <!-- Nama (Merk) -->
            <div class="mb-4">
                <label class="form-label text-white small fw-bold">
                    <i class="bi bi-tag me-1 text-accent"></i> Nama (Merk) <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" class="form-control text-white" value="{{ old('name', $engineValve->name) }}" placeholder="Contoh: Yamaha, AHM, Suzuki" required style="background: var(--surface-solid); border-color: var(--border);">
                <div class="form-text text-muted small">Contoh: Yamaha, AHM, Suzuki, Kawasaki, Honda, dll.</div>
            </div>

            <!-- Kode Barang -->
            <div class="mb-4">
                <label class="form-label text-white small fw-bold">
                    <i class="bi bi-upc-scan me-1 text-accent"></i> Kode Barang <span class="text-danger">*</span>
                </label>
                <input type="text" name="code" class="form-control mono-font text-white" value="{{ old('code', $engineValve->code) }}" placeholder="Masukkan kode barang" required style="background: var(--surface-solid); border-color: var(--border);">
                <div class="form-text text-muted small">Isi kode part/barang sesuai standar penamaan Anda.</div>
            </div>

            <!-- Tipe Katup (Dropdown Intake / Exhaust) -->
            <div class="mb-4">
                <label class="form-label text-white small fw-bold">
                    <i class="bi bi-layers me-1 text-accent"></i> Tipe Katup <span class="text-danger">*</span>
                </label>
                <select name="type" class="form-select text-white" required style="background: var(--surface-solid); border-color: var(--border);">
                    <option value="" disabled>-- Pilih Tipe (Intake / Exhaust) --</option>
                    <option value="Intake" {{ old('type', $engineValve->type) == 'Intake' ? 'selected' : '' }}>Intake</option>
                    <option value="Exhaust" {{ old('type', $engineValve->type) == 'Exhaust' ? 'selected' : '' }}>Exhaust</option>
                </select>
                <div class="form-text text-muted small">Pilihan tipe katup: <strong>Intake</strong> (katup hisap) atau <strong>Exhaust</strong> (katup buang).</div>
            </div>

            <div class="d-flex gap-2 pt-2 border-top border-secondary border-opacity-10">
                <button type="submit" class="btn btn-cyber">
                    <i class="bi bi-check-lg me-1"></i> Perbarui Data Valve
                </button>
                <a href="{{ route('supervisor.engine-valves.index') }}" class="btn btn-soft-action">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection