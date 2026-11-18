@extends('layouts.app')
@section('title', 'Daftar Operator Pengerjaan')

@section('content')
<div class="fade-in">
    <!-- Header Banner -->
    <div class="page-header-box">
        <div class="d-flex align-items-center gap-3">
            <div class="page-header-icon" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <h4 class="page-header-title">Daftar Operator Pengerjaan Packing</h4>
                <p class="page-header-desc">Manajemen akun operator workstation, nomor pokok karyawan (NPK), dan penetapan shift kerja lini packaging.</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('supervisor.operators.create') }}" class="btn btn-cyber">
                <i class="bi bi-person-plus-fill me-1"></i> Daftarkan Operator Baru
            </a>
            <a href="{{ route('supervisor.dashboard') }}" class="btn btn-cyber-outline">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Metric Chips to prevent emptiness -->
    <div class="stat-chip-group">
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8;">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <div class="stat-chip-val">{{ $totalOperators }} Operator</div>
                <div class="stat-chip-title">Total Akun Terdaftar</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                <i class="bi bi-shield-lock"></i>
            </div>
            <div>
                <div class="stat-chip-val">NPK & SHIFT</div>
                <div class="stat-chip-title">Autentikasi Terverifikasi</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div class="stat-chip-val">3 SHIFT</div>
                <div class="stat-chip-title">Operasional Lini FNI</div>
            </div>
        </div>
    </div>

    <!-- Operator List Card -->
    <div class="cyber-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-card-checklist text-accent"></i> Master Data Operator Lini Packing
                </h5>
                <span class="text-muted small">Cari berdasarkan Nama Operator, NPK, atau Username</span>
            </div>

            <div class="search-box-modern flex-grow-1 flex-sm-grow-0" style="min-width: 260px;">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control form-control-sm table-search-input" placeholder="Cari nama, NPK, username...">
            </div>
        </div>

        <div class="table-responsive" data-filterable-table>
            <table class="table table-cyber align-middle">
                <thead>
                    <tr>
                        <th width="120">NPK</th>
                        <th>Nama Operator</th>
                        <th>Username Login</th>
                        <th>Shift Default</th>
                        <th>Tugas Aktif</th>
                        <th>Tugas Selesai</th>
                        <th class="text-end" width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($operators as $operator)
                        <tr>
                            <td>
                                <span class="badge mono-font" style="background: rgba(0, 245, 212, 0.15); color: var(--accent); font-size: 0.76rem; letter-spacing: 0.5px;">
                                    {{ $operator->npk ?? 'OP-NEW' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem; background: linear-gradient(135deg, var(--accent), var(--accent-secondary)); color: #000;">
                                        {{ strtoupper(substr($operator->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-white small">{{ $operator->name }}</div>
                                        <span class="text-muted" style="font-size: 0.7rem;">{{ $operator->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="mono-font text-white small">{{ $operator->username ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; font-size: 0.72rem;">
                                    {{ $operator->shift ?? 'Shift 1' }}
                                </span>
                            </td>
                            <td>
                                @if($operator->active_tasks_count > 0)
                                    <span class="badge" style="background: rgba(14, 165, 233, 0.2); color: #38bdf8; font-size: 0.72rem;">
                                        {{ $operator->active_tasks_count }} Tugas
                                    </span>
                                @else
                                    <span class="text-muted small">0 Tugas</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-size: 0.72rem;">
                                    {{ $operator->completed_tasks_count }} Selesai
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('supervisor.operators.edit', $operator) }}" class="btn btn-sm btn-soft-action py-1 px-2" title="Edit Akun Operator">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('supervisor.operators.destroy', $operator) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun operator {{ $operator->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft-action py-1 px-2 text-danger" title="Hapus Operator">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-people fs-2 d-block mb-2 text-muted"></i>
                                Belum ada akun operator yang didaftarkan.
                                <div class="mt-2">
                                    <a href="{{ route('supervisor.operators.create') }}" class="btn btn-sm btn-cyber">
                                        <i class="bi bi-person-plus-fill me-1"></i> Daftarkan Operator Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr class="empty-filter-row" style="display: none;">
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
                            Tidak ditemukan operator yang cocok dengan pencarian Anda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Menampilkan {{ $operators->firstItem() ?? 0 }} - {{ $operators->lastItem() ?? 0 }} dari {{ $operators->total() }} operator</span>
            <div>
                {{ $operators->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
