@extends('layouts.app')
@section('title', 'Penugasan Surat Jalan')

@section('content')
@php
    $totalShipments = \App\Models\Shipment::where('created_by', auth()->id())->count();
    $totalTasksReady = \App\Models\PackagingTask::where('created_by', auth()->id())
        ->where('status', 'completed')
        ->whereDoesntHave('shipmentItems')
        ->count();
@endphp

<div class="fade-in">
    <!-- Header Banner -->
    <div class="page-header-box">
        <div class="d-flex align-items-center gap-3">
            <div class="page-header-icon" style="background: rgba(0, 245, 212, 0.12); color: var(--accent);">
                <i class="bi bi-truck"></i>
            </div>
            <div>
                <h4 class="page-header-title">Arsip Surat Jalan & Pengiriman</h4>
                <p class="page-header-desc">Dokumentasi legal pengiriman logistik valve yang telah lulus QC untuk serah terima ke gudang / ekspedisi.</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('supervisor.shipments.create') }}" class="btn btn-cyber">
                <i class="bi bi-plus-circle-fill me-1"></i> Buat Surat Jalan
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
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div>
                <div class="stat-chip-val">{{ $totalShipments }}</div>
                <div class="stat-chip-title">Total Surat Jalan Terbit</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div>
                <div class="stat-chip-val" style="color: #34d399;">{{ $totalTasksReady }} Tugas</div>
                <div class="stat-chip-title">Siap Dimuat ke Surat Jalan</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                <i class="bi bi-file-earmark-pdf"></i>
            </div>
            <div>
                <div class="stat-chip-val">DOMPDF A4</div>
                <div class="stat-chip-title">Format Standar Dokumen</div>
            </div>
        </div>
    </div>

    <!-- Shipments Table Card -->
    <div class="cyber-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                    <i class="bi bi-card-list text-accent"></i> Daftar Dokumen Surat Jalan
                </h5>
                <span class="text-muted small">Cari berdasarkan nomor surat jalan, nama driver kurir, atau plat nomor kendaraan</span>
            </div>

            <div class="search-box-modern flex-grow-1 flex-sm-grow-0" style="min-width: 260px;">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control form-control-sm table-search-input" placeholder="Cari surat jalan / driver...">
            </div>
        </div>

        <div class="table-responsive" data-filterable-table>
            <table class="table table-cyber align-middle">
                <thead>
                    <tr>
                        <th width="190">Kode Dokumen</th>
                        <th>Nama Driver / Kurir</th>
                        <th>No. Kendaraan</th>
                        <th>Tanggal Kirim</th>
                        <th>Jumlah Part Item</th>
                        <th class="text-end" width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shipments as $shipment)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="mono-font fw-bold text-accent small">{{ $shipment->shipment_code }}</span>
                                    <button type="button" class="copy-code-btn" onclick="copyTaskCode('{{ $shipment->shipment_code }}', this)" title="Salin Kode">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-white small">
                                    <i class="bi bi-person me-1 text-muted"></i> {{ $shipment->driver_name }}
                                </div>
                            </td>
                            <td>
                                <span class="mono-font text-white small badge" style="background: rgba(255,255,255,0.06); border: 1px solid var(--border);">
                                    {{ $shipment->vehicle_no ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted mono-font">
                                    {{ \Carbon\Carbon::parse($shipment->shipment_date)->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(0, 245, 212, 0.15); color: var(--accent); font-family: var(--font-mono); font-size: 0.72rem;">
                                    {{ $shipment->items->count() }} Jenis Valve
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('supervisor.shipments.show', $shipment) }}" class="btn btn-sm btn-soft-action py-1 px-2" title="Detail Dokumen">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('supervisor.shipments.pdf', $shipment) }}" class="btn btn-sm btn-cyber py-1 px-2" title="Unduh PDF Resmi">
                                        <i class="bi bi-file-earmark-pdf"></i> PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-truck fs-2 d-block mb-2 text-muted"></i>
                                Belum ada dokumen surat jalan yang diterbitkan.
                                <div class="mt-2">
                                    <a href="{{ route('supervisor.shipments.create') }}" class="btn btn-sm btn-cyber">
                                        <i class="bi bi-plus-circle me-1"></i> Buat Surat Jalan Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr class="empty-filter-row" style="display: none;">
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
                            Tidak ada surat jalan yang sesuai dengan kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Menampilkan data {{ $shipments->firstItem() ?? 0 }} - {{ $shipments->lastItem() ?? 0 }} dari {{ $shipments->total() }} surat jalan</span>
            <div>
                {{ $shipments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection