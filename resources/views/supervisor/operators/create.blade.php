@extends('layouts.app')
@section('title', 'Daftarkan Operator Baru')

@section('content')
<div class="fade-in mx-auto" style="max-width: 680px;">
    <!-- Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('supervisor.operators.index') }}" class="btn btn-sm btn-cyber-outline">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Operator
        </a>
        <span class="hud-telemetry-chip py-1 px-2">
            <span class="pulse-dot"></span> REGISTRASI OPERATOR
        </span>
    </div>

    <div class="cyber-card p-4 p-md-5">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-15">
            <div class="page-header-icon" style="background: rgba(14, 165, 233, 0.12); color: #38bdf8;">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <div>
                <h4 class="fw-bold text-white mb-0">Daftarkan Akun Operator Baru</h4>
                <p class="text-muted small mb-0">Operator akan menggunakan Username, NPK, dan Password ini untuk login ke workstation.</p>
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

        <form method="POST" action="{{ route('supervisor.operators.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label text-white small fw-bold">Nama Lengkap Operator</label>
                <input type="text" name="name" class="form-control text-white" placeholder="Contoh: Budi Pratama" value="{{ old('name') }}" required style="background: var(--surface-solid); border-color: var(--border);">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">NPK (Nomor Pokok Karyawan)</label>
                    <input type="text" name="npk" class="form-control mono-font text-white" placeholder="Contoh: OP-002 atau 240102" value="{{ old('npk') }}" required style="background: var(--surface-solid); border-color: var(--border);">
                    <small class="text-muted" style="font-size: 0.72rem;">Harus unik untuk tiap karyawan.</small>
                </div>

                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">Username Login</label>
                    <input type="text" name="username" class="form-control mono-font text-white" placeholder="Contoh: budi_op" value="{{ old('username') }}" required style="background: var(--surface-solid); border-color: var(--border);">
                    <small class="text-muted" style="font-size: 0.72rem;">Digunakan saat login stasiun.</small>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">Shift Kerja Default</label>
                    <select name="shift" class="form-select" required style="background: var(--surface-solid); border-color: var(--border); color: var(--text-bright);">
                        <option value="Shift 1 (07:00 - 15:30)" {{ old('shift') == 'Shift 1 (07:00 - 15:30)' ? 'selected' : '' }}>Shift 1 (07:00 - 15:30 WIB)</option>
                        <option value="Shift 2 (15:30 - 23:00)" {{ old('shift') == 'Shift 2 (15:30 - 23:00)' ? 'selected' : '' }}>Shift 2 (15:30 - 23:00 WIB)</option>
                        <option value="Shift 3 (23:00 - 07:00)" {{ old('shift') == 'Shift 3 (23:00 - 07:00)' ? 'selected' : '' }}>Shift 3 (23:00 - 07:00 WIB)</option>
                    </select>
                </div>

                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">Password Akun</label>
                    <input type="password" name="password" class="form-control text-white" placeholder="Minimal 4 karakter" required style="background: var(--surface-solid); border-color: var(--border);">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-cyber">
                    <i class="bi bi-check-circle-fill me-1"></i> Daftarkan Akun Operator
                </button>
                <a href="{{ route('supervisor.operators.index') }}" class="btn btn-soft-action">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
