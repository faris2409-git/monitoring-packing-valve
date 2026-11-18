@extends('layouts.app')
@section('title', 'Data Engine Valve')

@section('content')
<div class="fade-in">
    <!-- Header Banner -->
    <div class="page-header-box">
        <div class="d-flex align-items-center gap-3">
            <div class="page-header-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <div>
                <h4 class="page-header-title">Data Master Engine Valve</h4>
                <p class="page-header-desc">Katalog master engine valve berdasarkan merk (Yamaha, AHM, Suzuki), kode barang, dan tipe katup.</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('supervisor.engine-valves.create') }}" class="btn btn-cyber">
                <i class="bi bi-plus-circle-fill me-1"></i> Tambah Valve Baru
            </a>
            <a href="{{ route('supervisor.dashboard') }}" class="btn btn-cyber-outline">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Metric Chips -->
    <div class="stat-chip-group">
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8;">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <div class="stat-chip-val">{{ $totalCount ?? $valves->total() }}</div>
                <div class="stat-chip-title">Total Valve Terdaftar</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(0, 245, 212, 0.15); color: var(--accent);">
                <i class="bi bi-box-arrow-in-down"></i>
            </div>
            <div>
                <div class="stat-chip-val">{{ $intakeCount ?? 0 }}</div>
                <div class="stat-chip-title">Tipe Intake</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(244, 63, 94, 0.15); color: #fb7185;">
                <i class="bi bi-box-arrow-up-right"></i>
            </div>
            <div>
                <div class="stat-chip-val">{{ $exhaustCount ?? 0 }}</div>
                <div class="stat-chip-title">Tipe Exhaust</div>
            </div>
        </div>
    </div>

    <!-- Valve Table Card -->
    <div class="cyber-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-list-columns-reverse text-accent"></i> Daftar Engine Valve
                </h5>
                <span class="text-muted small">Cari berdasarkan merk, kode barang, atau tipe (Intake / Exhaust)</span>
            </div>

            <div class="search-box-modern flex-grow-1 flex-sm-grow-0" style="min-width: 280px;">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control form-control-sm table-search-input" placeholder="Cari merk, kode barang, tipe...">
            </div>
        </div>

        <div class="table-responsive" data-filterable-table>
            <table class="table table-cyber align-middle">
                <thead>
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th>Nama (Merk)</th>
                        <th width="220">Kode Barang</th>
                        <th width="160">Tipe Katup</th>
                        <th class="text-end" width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($valves as $index => $valve)
                        <tr>
                            <td class="text-center text-muted small mono-font">
                                {{ $valves->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="fw-bold text-white fs-6">
                                    <i class="bi bi-tag-fill me-1 text-accent small"></i> {{ $valve->name }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="mono-font fw-bold text-accent">{{ $valve->code }}</span>
                                    <button type="button" class="copy-code-btn" onclick="copyTaskCode('{{ $valve->code }}', this)" title="Salin Kode Barang">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                @if(strcasecmp($valve->type, 'Intake') === 0)
                                    <span class="badge" style="background: rgba(0, 245, 212, 0.15); color: var(--accent); border: 1px solid rgba(0, 245, 212, 0.3); font-size: 0.78rem; padding: 5px 10px;">
                                        <i class="bi bi-box-arrow-in-down me-1"></i> Intake
                                    </span>
                                @elseif(strcasecmp($valve->type, 'Exhaust') === 0)
                                    <span class="badge" style="background: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3); font-size: 0.78rem; padding: 5px 10px;">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Exhaust
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ $valve->type ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('supervisor.engine-valves.edit', $valve) }}" class="btn btn-sm btn-soft-action py-1 px-2" title="Edit Data Valve">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('supervisor.engine-valves.destroy', $valve) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data engine valve {{ $valve->name }} ({{ $valve->code }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft-action py-1 px-2 text-danger" title="Hapus Valve">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-gear fs-2 d-block mb-2 text-muted"></i>
                                Belum ada data engine valve terdaftar.
                                <div class="mt-2">
                                    <a href="{{ route('supervisor.engine-valves.create') }}" class="btn btn-sm btn-cyber">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Engine Valve Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr class="empty-filter-row" style="display: none;">
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
                            Tidak ditemukan engine valve yang sesuai dengan pencarian Anda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Menampilkan data {{ $valves->firstItem() ?? 0 }} - {{ $valves->lastItem() ?? 0 }} dari {{ $valves->total() }} engine valve</span>
            <div>
                {{ $valves->links() }}
            </div>
        </div>
    </div>
</div>
@endsection