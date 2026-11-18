@extends('layouts.app')
@section('title', 'Edit Data Operator: ' . $operator->name)

@section('content')
<div class="fade-in mx-auto" style="max-width: 680px;">
    <!-- Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('supervisor.operators.index') }}" class="btn btn-sm btn-cyber-outline">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Operator
        </a>
        <span class="hud-telemetry-chip py-1 px-2">
            <span class="pulse-dot"></span> EDIT OPERATOR
        </span>
    </div>

    <div class="cyber-card p-4 p-md-5">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-15">
            <div class="page-header-icon" style="background: rgba(14, 165, 233, 0.12); color: #38bdf8;">
                <i class="bi bi-person-gear"></i>
            </div>
            <div>
                <span class="badge mono-font mb-1" style="background: rgba(0, 245, 212, 0.15); color: var(--accent);">NPK: {{ $operator->npk }}</span>
                <h4 class="fw-bold text-white mb-0">Ubah Data Akun Operator</h4>
                <p class="text-muted small mb-0">Perbarui identitas, shift kerja, atau ganti password akun operator.</p>
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

        <form method="POST" action="{{ route('supervisor.operators.update', $operator) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label text-white small fw-bold">Nama Lengkap Operator</label>
                <input type="text" name="name" class="form-control text-white" value="{{ old('name', $operator->name) }}" required style="background: var(--surface-solid); border-color: var(--border);">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">NPK (Nomor Pokok Karyawan)</label>
                    <input type="text" name="npk" class="form-control mono-font text-white" value="{{ old('npk', $operator->npk) }}" required style="background: var(--surface-solid); border-color: var(--border);">
                </div>

                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">Username Login</label>
                    <input type="text" name="username" class="form-control mono-font text-white" value="{{ old('username', $operator->username) }}" required style="background: var(--surface-solid); border-color: var(--border);">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">Shift Kerja Default</label>
                    <select name="shift" class="form-select" required style="background: var(--surface-solid); border-color: var(--border); color: var(--text-bright);">
                        <option value="Shift 1 (07:00 - 15:30)" {{ old('shift', $operator->shift) == 'Shift 1 (07:00 - 15:30)' ? 'selected' : '' }}>Shift 1 (07:00 - 15:30 WIB)</option>
                        <option value="Shift 2 (15:30 - 23:00)" {{ old('shift', $operator->shift) == 'Shift 2 (15:30 - 23:00)' ? 'selected' : '' }}>Shift 2 (15:30 - 23:00 WIB)</option>
                        <option value="Shift 3 (23:00 - 07:00)" {{ old('shift', $operator->shift) == 'Shift 3 (23:00 - 07:00)' ? 'selected' : '' }}>Shift 3 (23:00 - 07:00 WIB)</option>
                    </select>
                </div>

                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">Ganti Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control text-white" placeholder="Biarkan kosong jika tidak ganti" style="background: var(--surface-solid); border-color: var(--border);">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-cyber">
                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('supervisor.operators.index') }}" class="btn btn-soft-action">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
