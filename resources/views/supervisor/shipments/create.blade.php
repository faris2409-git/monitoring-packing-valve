@extends('layouts.app')
@section('title', 'Buat Surat Jalan')

@section('content')
<div class="fade-in mx-auto" style="max-width: 760px;">
    <!-- Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('supervisor.shipments.index') }}" class="btn btn-sm btn-cyber-outline">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Surat Jalan
        </a>
        <span class="hud-telemetry-chip py-1 px-2">
            <span class="pulse-dot"></span> FORM LOGISTIK
        </span>
    </div>

    <div class="cyber-card p-4 p-md-5">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-15">
            <div class="page-header-icon" style="background: rgba(0, 245, 212, 0.12); color: var(--accent);">
                <i class="bi bi-truck"></i>
            </div>
            <div>
                <h4 class="fw-bold text-white mb-0">Penerbitan Surat Jalan Pengiriman</h4>
                <p class="text-muted small mb-0">Gabungkan paket valve yang telah selesai dipacking dan tentukan data armada kurir.</p>
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

        <form method="POST" action="{{ route('supervisor.shipments.store') }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">Nama Driver / Ekspedisi</label>
                    <input type="text" name="driver_name" class="form-control text-white" placeholder="Contoh: Budi Santoso / JNE Cargo" value="{{ old('driver_name') }}" required style="background: var(--surface-solid); border-color: var(--border);">
                </div>

                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">No. Plat Kendaraan / Polisi</label>
                    <input type="text" name="vehicle_no" class="form-control mono-font text-white" placeholder="Contoh: B 9821 FNI" value="{{ old('vehicle_no') }}" style="background: var(--surface-solid); border-color: var(--border);">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">Tanggal Pengiriman</label>
                    <input type="date" name="shipment_date" class="form-control mono-font text-white" value="{{ old('shipment_date', now()->format('Y-m-d')) }}" required style="background: var(--surface-solid); border-color: var(--border);">
                </div>

                <div class="col-sm-6">
                    <label class="form-label text-white small fw-bold">Catatan Ekspedisi (Opsional)</label>
                    <input type="text" name="notes" class="form-control text-white" placeholder="Contoh: Muatan Valve Line 1 tujuan Gudang Barat" value="{{ old('notes') }}" style="background: var(--surface-solid); border-color: var(--border);">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-white small fw-bold d-flex justify-content-between align-items-center">
                    <span>Pilih Batch Valve Selesai Dipacking:</span>
                    <span class="text-accent small mono-font">{{ $availableTasks->count() }} Batch Tersedia</span>
                </label>

                @if ($availableTasks->isEmpty())
                    <div class="p-4 rounded-3 text-center" style="background: var(--surface-subtle); border: 1px dashed var(--border);">
                        <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                        <span class="text-muted small">Belum ada tugas packaging dengan status 'Selesai' yang belum dimuat.</span>
                    </div>
                @else
                    <div class="table-responsive rounded-3 p-2" style="background: var(--surface-subtle); border: 1px solid var(--border); max-height: 280px; overflow-y: auto;">
                        <table class="table table-cyber align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="45" class="text-center">PILIH</th>
                                    <th>Kode Tugas</th>
                                    <th>Engine Valve</th>
                                    <th>Qty Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($availableTasks as $task)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="task_ids[]" value="{{ $task->id }}" class="form-check-input" style="cursor: pointer;">
                                        </td>
                                        <td>
                                            <span class="mono-font fw-bold text-accent small">{{ $task->task_code }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-white small">{{ $task->engineValve->name }}</span>
                                        </td>
                                        <td>
                                            <span class="mono-font text-white small fw-bold">{{ $task->quantity_packed }} Pcs</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">Centang satu atau beberapa batch tugas di atas yang akan dimuat ke surat jalan ini.</small>
                @endif
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-cyber" {{ $availableTasks->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-file-earmark-check me-1"></i> Terbitkan Surat Jalan
                </button>
                <a href="{{ route('supervisor.shipments.index') }}" class="btn btn-soft-action">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection