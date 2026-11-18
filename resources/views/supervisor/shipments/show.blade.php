@extends('layouts.app')
@section('title', 'Detail Surat Jalan: ' . $shipment->shipment_code)

@section('content')
<div class="fade-in mx-auto" style="max-width: 820px;">
    <!-- Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('supervisor.shipments.index') }}" class="btn btn-sm btn-cyber-outline">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Surat Jalan
        </a>
        <a href="{{ route('supervisor.shipments.pdf', $shipment) }}" class="btn btn-sm btn-cyber">
            <i class="bi bi-file-earmark-pdf me-1"></i> Unduh / Cetak Dokumen PDF
        </a>
    </div>

    <!-- Main Card -->
    <div class="cyber-card p-4 p-md-5 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 pb-3 mb-4 border-bottom border-secondary border-opacity-15">
            <div>
                <div class="hud-telemetry-chip mb-2">
                    <span class="pulse-dot"></span>
                    <span>SURAT JALAN RESMI // PT FNI</span>
                </div>
                <h3 class="fw-bold text-white mb-1">{{ $shipment->shipment_code }}</h3>
                <span class="mono-font text-muted small">Tanggal: {{ \Carbon\Carbon::parse($shipment->shipment_date)->format('d F Y') }}</span>
            </div>

            <div class="text-end">
                <div class="mono-font text-muted small">Diterbitkan Oleh</div>
                <div class="fw-bold text-white">{{ $shipment->creator->name ?? auth()->user()->name }}</div>
                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-size: 0.68rem;">QC APPROVED FOR DISPATCH</span>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <div class="p-3 rounded-3 h-100" style="background: var(--surface-solid); border: 1px solid var(--border-subtle);">
                    <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Driver / Kurir</div>
                    <div class="fw-bold text-white"><i class="bi bi-person me-1 text-accent"></i> {{ $shipment->driver_name }}</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="p-3 rounded-3 h-100" style="background: var(--surface-solid); border: 1px solid var(--border-subtle);">
                    <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">No. Plat Kendaraan</div>
                    <div class="mono-font fw-bold text-white"><i class="bi bi-truck me-1 text-accent"></i> {{ $shipment->vehicle_no ?? '-' }}</div>
                </div>
            </div>
            @if($shipment->notes)
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background: var(--surface-solid); border: 1px solid var(--border-subtle);">
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Catatan Pengiriman</div>
                        <div class="text-white small">{{ $shipment->notes }}</div>
                    </div>
                </div>
            @endif
        </div>

        <h6 class="fw-bold text-white mb-2 d-flex align-items-center gap-2">
            <i class="bi bi-boxes text-accent"></i> Daftar Muatan Katup Valve ({{ $shipment->items->count() }} Item)
        </h6>

        <div class="table-responsive">
            <table class="table table-cyber align-middle">
                <thead>
                    <tr>
                        <th>Kode Tugas</th>
                        <th>Engine Valve</th>
                        <th class="text-end">Jumlah Dimuat</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach ($shipment->items as $item)
                        @php $grandTotal += $item->quantity; @endphp
                        <tr>
                            <td>
                                <span class="mono-font fw-bold text-accent small">{{ $item->packagingTask->task_code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-white small">{{ $item->packagingTask->engineValve->name }}</div>
                                <span class="mono-font text-muted" style="font-size: 0.72rem;">{{ $item->packagingTask->engineValve->code }}</span>
                            </td>
                            <td class="text-end">
                                <span class="mono-font fw-bold text-white">{{ number_format($item->quantity) }} Pcs</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-top border-secondary border-opacity-25">
                        <th colspan="2" class="text-white fw-bold">TOTAL MUATAN PENGIRIMAN:</th>
                        <th class="text-end mono-font text-accent fw-bold fs-6">{{ number_format($grandTotal) }} Pcs</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection