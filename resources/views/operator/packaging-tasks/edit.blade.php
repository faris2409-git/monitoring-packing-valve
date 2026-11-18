@extends('layouts.app')
@section('title', 'Input Progres: ' . $packagingTask->task_code)

@section('content')
@php
    $percent = $packagingTask->quantity_target > 0
        ? min(100, round(($packagingTask->quantity_packed / $packagingTask->quantity_target) * 100))
        : 0;
    $isCompleteReady = $packagingTask->quantity_packed >= $packagingTask->quantity_target;
@endphp

<div class="fade-in mx-auto" style="max-width: 680px;">
    <!-- Breadcrumb Action -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('operator.packaging-tasks.work') }}" class="btn btn-sm btn-cyber-outline">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Pengerjaan
        </a>
        <span class="badge py-1 px-3" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8; font-size: 0.78rem;">
            <i class="bi bi-gear-wide me-1"></i> AKTIF DIKERJAKAN
        </span>
    </div>

    <!-- Main Card -->
    <div class="cyber-card p-4 p-md-5 mb-4">
        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-15">
            <div class="page-header-icon" style="background: rgba(0, 245, 212, 0.12); color: var(--accent);">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <span class="mono-font text-accent fw-bold small">{{ $packagingTask->task_code }}</span>
                <h4 class="fw-bold text-white mb-0">{{ $packagingTask->engineValve->name }}</h4>
                <div class="text-muted small">Part Code: {{ $packagingTask->engineValve->code }} • Target: {{ $packagingTask->quantity_target }} Unit</div>
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

        <!-- Target Delivery Date Box -->
        <div class="p-3 rounded-3 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: rgba(14, 165, 233, 0.08); border: 1px solid rgba(14, 165, 233, 0.25);">
            <div>
                <div class="text-accent small text-uppercase fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                    <i class="bi bi-calendar2-check-fill me-1"></i> Target Tanggal Pengiriman
                </div>
                <div class="fs-6 fw-bold text-white mono-font">
                    {{ $packagingTask->target_delivery_date ? \Carbon\Carbon::parse($packagingTask->target_delivery_date)->translatedFormat('l, d F Y') : 'Tidak Ditetapkan' }}
                </div>
            </div>
            @if($packagingTask->target_delivery_date)
                @php
                    $tDate = \Carbon\Carbon::parse($packagingTask->target_delivery_date)->startOfDay();
                    $today = \Carbon\Carbon::now()->startOfDay();
                    $diff = $today->diffInDays($tDate, false);
                @endphp
                <div>
                    @if($diff < 0)
                        <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #f87171; font-size: 0.75rem;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Terlambat {{ abs($diff) }} Hari
                        </span>
                    @elseif($diff === 0)
                        <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; font-size: 0.75rem;">
                            <i class="bi bi-clock-fill me-1"></i> Deadline Hari Ini
                        </span>
                    @else
                        <span class="badge" style="background: rgba(56, 189, 248, 0.2); color: #38bdf8; font-size: 0.75rem;">
                            <i class="bi bi-hourglass-split me-1"></i> Sisa {{ $diff }} Hari
                        </span>
                    @endif
                </div>
            @endif
        </div>

        @if ($packagingTask->instruction)
            <div class="p-3 rounded-3 mb-4" style="background: var(--surface-subtle); border: 1px solid var(--border);">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-info-circle-fill text-accent"></i>
                    <strong class="text-white small">Instruksi Supervisor:</strong>
                </div>
                <div class="small text-muted ps-4">{{ $packagingTask->instruction }}</div>
            </div>
        @endif

        <!-- Real-Time Progress Bar -->
        <div class="p-3 rounded-3 mb-4" style="background: var(--surface-subtle); border: 1px solid var(--border);">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Progres Hasil Selesai:</span>
                <span class="mono-font fw-bold text-white small">
                    <span class="text-accent" id="livePackedCount">{{ $packagingTask->quantity_packed }}</span> / {{ $packagingTask->quantity_target }} Unit (<span id="livePackedPercent">{{ $percent }}</span>%)
                </span>
            </div>
            <div class="progress-modern">
                <div class="progress-bar-modern" id="liveProgressBar" style="width: {{ $percent }}%; height: 8px;"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('operator.packaging-tasks.update', $packagingTask) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label text-white small fw-bold">Quantity Sudah Dipacking (Unit)</label>
                <div class="input-group">
                    <span class="input-group-text" style="background: var(--surface-solid); border-color: var(--border); color: var(--accent);">
                        <i class="bi bi-box"></i>
                    </span>
                    <input type="number" name="quantity_packed" id="quantityPackedInput" class="form-control mono-font fw-bold text-white" min="0" max="{{ $packagingTask->quantity_target }}" value="{{ old('quantity_packed', $packagingTask->quantity_packed) }}" required style="background: var(--surface-solid); border-color: var(--border); font-size: 1.1rem;">
                    <span class="input-group-text text-muted small" style="background: var(--surface-solid); border-color: var(--border);">
                        / {{ $packagingTask->quantity_target }} pcs
                    </span>
                </div>
                <small class="text-muted" style="font-size: 0.75rem;">Masukkan akumulasi jumlah valve yang telah lolos QC dan tersegel.</small>
            </div>

            <div class="mb-4">
                <label class="form-label text-white small fw-bold">Catatan Pengerjaan / Laporan Kendala (Opsional)</label>
                <textarea name="notes" class="form-control small" rows="3" placeholder="Contoh: 5 unit katup memiliki goresan mikro pada chamfer, menunggu part pengganti..." style="background: var(--surface-solid); border-color: var(--border); color: var(--text-bright);">{{ old('notes', $packagingTask->notes) }}</textarea>
            </div>

            <div class="d-flex flex-column gap-3">
                <!-- Helper Notice for Locked Complete Button -->
                <div id="incompleteTargetWarning" class="p-3 rounded-3 small d-flex align-items-center gap-2" style="background: rgba(245, 158, 11, 0.1); border: 1px dashed rgba(245, 158, 11, 0.35); color: #fbbf24; {{ $isCompleteReady ? 'display: none !important;' : '' }}">
                    <i class="bi bi-lock-fill fs-5"></i>
                    <div>
                        <strong>Tombol Selesai Terkunci:</strong> Target belum tercapai (<span id="warnPackedCount">{{ $packagingTask->quantity_packed }}</span> / {{ $packagingTask->quantity_target }} unit). Anda hanya dapat <em>Simpan Progres Sementara</em> sampai pekerjaan selesai 100%.
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <button type="submit" name="action" value="save" class="btn btn-cyber-outline">
                        <i class="bi bi-save me-1"></i> Simpan Progres Sementara
                    </button>
                    <button type="submit" name="action" value="complete" id="btnCompleteTask" class="btn btn-cyber {{ !$isCompleteReady ? 'opacity-50' : '' }}" {{ !$isCompleteReady ? 'disabled' : '' }} onclick="return confirm('Apakah target packaging telah tuntas 100% dan siap diserahkan ke supervisor?')">
                        <i class="bi bi-check-circle-fill me-1"></i> Tuntas & Selesaikan Tugas
                    </button>
                    <a href="{{ route('operator.packaging-tasks.work') }}" class="btn btn-soft-action ms-auto">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const targetQty = {{ $packagingTask->quantity_target }};
    const inputEl = document.getElementById('quantityPackedInput');
    const liveCountEl = document.getElementById('livePackedCount');
    const livePercentEl = document.getElementById('livePackedPercent');
    const liveBarEl = document.getElementById('liveProgressBar');
    const btnComplete = document.getElementById('btnCompleteTask');
    const warnEl = document.getElementById('incompleteTargetWarning');
    const warnCountEl = document.getElementById('warnPackedCount');

    function syncPackagingState() {
        if (!inputEl) return;
        let val = parseInt(inputEl.value) || 0;
        if (val > targetQty) val = targetQty;
        if (val < 0) val = 0;

        if (liveCountEl) liveCountEl.innerText = val;
        if (warnCountEl) warnCountEl.innerText = val;

        let pct = targetQty > 0 ? Math.min(100, Math.round((val / targetQty) * 100)) : 0;
        if (livePercentEl) livePercentEl.innerText = pct;
        if (liveBarEl) liveBarEl.style.width = pct + '%';

        if (val >= targetQty && targetQty > 0) {
            if (btnComplete) {
                btnComplete.disabled = false;
                btnComplete.classList.remove('opacity-50');
            }
            if (warnEl) warnEl.style.setProperty('display', 'none', 'important');
        } else {
            if (btnComplete) {
                btnComplete.disabled = true;
                btnComplete.classList.add('opacity-50');
            }
            if (warnEl) warnEl.style.setProperty('display', 'flex', 'important');
        }
    }

    if (inputEl) {
        inputEl.addEventListener('input', syncPackagingState);
        syncPackagingState();
    }
</script>
@endsection