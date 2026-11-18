/**
 * FNI Valve Packaging Telemetry System - Modern Interactive Engine
 * Clean, lightweight, professional corporate performance.
 */

// Sound feature removed per user request - safe no-op stub provided
window.cyberSound = {
    enabled: false,
    init: () => {},
    toggle: () => false,
    updateButtons: () => {},
    playTone: () => {},
    playHover: () => {},
    playClick: () => {}
};

function initCyberCanvas() {
    // Canvas disabled for clean enterprise interface
}

// ==================== 3. (REMOVED - CLOCK) ====================

// ==================== 4. LIVE DASHBOARD TABLE FILTER & SEARCH ====================
function initDashboardLiveFilters() {
    const tableContainers = document.querySelectorAll('[data-filterable-table]');
    if (!tableContainers.length) return;

    tableContainers.forEach(tableContainer => {
        const card = tableContainer.closest('.cyber-card') || document;
        const rows = tableContainer.querySelectorAll('tbody tr:not(.empty-filter-row)');
        const filterButtons = card.querySelectorAll('.filter-tab-btn');
        const searchInput = card.querySelector('.table-search-input');
        const emptyRow = tableContainer.querySelector('.empty-filter-row');

        let currentStatus = 'all';
        let currentQuery = '';

        function applyFilter() {
            let visibleCount = 0;

            rows.forEach(row => {
                const rowStatus = (row.getAttribute('data-status') || '').trim().toLowerCase();
                const rowText = row.innerText.toLowerCase();

                const matchesStatus = (currentStatus === 'all') || (rowStatus === currentStatus) || (!row.hasAttribute('data-status'));
                const matchesQuery = (currentQuery === '') || rowText.includes(currentQuery);

                if (matchesStatus && matchesQuery) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentStatus = (btn.getAttribute('data-filter') || 'all').trim().toLowerCase();
                applyFilter();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                currentQuery = e.target.value.trim().toLowerCase();
                applyFilter();
            });
        }
    });
}

// ==================== 5. CLICK TO COPY TASK CODE ====================
function copyTaskCode(code, btnElement) {
    if (!navigator.clipboard) {
        fallbackCopy(code);
    } else {
        navigator.clipboard.writeText(code).then(() => {
            feedbackCopy(btnElement, code);
        }).catch(() => fallbackCopy(code));
    }
}

function feedbackCopy(btnElement, code) {
    if (btnElement) {
        const originalHtml = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="bi bi-check2 text-success"></i>';
        setTimeout(() => {
            btnElement.innerHTML = originalHtml;
        }, 1500);
    }
    showFniToast(`Kode ${code} disalin!`, 'bi-clipboard-check');
}

function fallbackCopy(code) {
    const temp = document.createElement('textarea');
    temp.value = code;
    document.body.appendChild(temp);
    temp.select();
    document.execCommand('copy');
    document.body.removeChild(temp);
    showFniToast(`Kode ${code} disalin!`, 'bi-clipboard-check');
}

// ==================== 6. FLOATING TOAST NOTIFICATION ====================
function showFniToast(message, iconClass = 'bi-info-circle') {
    const existing = document.querySelector('.fni-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'fni-toast';
    toast.innerHTML = `
        <i class="bi ${iconClass} fs-5" style="color: var(--accent);"></i>
        <span class="small fw-semibold text-white">${message}</span>
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 2400);
}

// ==================== 7. OPERATOR QC CHECKLIST ====================
function initOperatorQC() {
    const qcItems = document.querySelectorAll('.qc-item');
    if (!qcItems.length) return;

    const storageKey = 'fni_operator_qc_' + new Date().toISOString().slice(0, 10);
    let savedState = {};
    try {
        savedState = JSON.parse(localStorage.getItem(storageKey)) || {};
    } catch (e) {}

    function updateProgress() {
        const total = qcItems.length;
        const checked = document.querySelectorAll('.qc-item.checked').length;
        const progressEl = document.getElementById('qcProgressText');
        const progressBar = document.getElementById('qcProgressBar');

        if (progressEl) progressEl.innerText = `${checked}/${total} Selesai`;
        if (progressBar) progressBar.style.width = `${Math.round((checked / total) * 100)}%`;
    }

    qcItems.forEach((item, index) => {
        if (savedState[index]) {
            item.classList.add('checked');
            const checkIcon = item.querySelector('.qc-checkbox i');
            if (checkIcon) checkIcon.className = 'bi bi-check-lg';
        }

        item.addEventListener('click', () => {
            item.classList.toggle('checked');
            const isChecked = item.classList.contains('checked');
            const checkIcon = item.querySelector('.qc-checkbox i');
            if (checkIcon) {
                checkIcon.className = isChecked ? 'bi bi-check-lg' : '';
            }

            savedState[index] = isChecked;
            localStorage.setItem(storageKey, JSON.stringify(savedState));
            updateProgress();

            if (isChecked && document.querySelectorAll('.qc-item.checked').length === qcItems.length) {
                showFniToast('Semua SOP Quality Control Terverifikasi!', 'bi-shield-fill-check');
            }
        });
    });
}

// ==================== 8. INTERACTIVE VALVE HOTSPOTS (LANDING) ====================
const valveHotspotData = {
    head: {
        title: "PAYUNG KATUP (VALVE HEAD & 45° SEAT)",
        spec: "Material: Heat-Resistant High-Nickel Alloy (SUH3 / SUH35)",
        detail: "Sudut kontak presisi 45° dengan toleransi kebulatan < 0.008 mm. Didesain untuk menahan temperatur pembakaran hingga 800°C dengan sealing anti bocor kompresi gas buang & hisap.",
        status: "CALIBRATED & VERIFIED"
    },
    stem: {
        title: "BATANG KLEP (STEM SHAFT MIRROR FINISH)",
        spec: "Diameter: Ø 5.50 mm ± 0.005 mm • Hard Chrome Plating",
        detail: "Finishing permukaan ultra-halus (Ra < 0.15 µm) mengurangi gesekan pada valve guide. Bebas goresan mikro untuk memastikan kelancaran pergerakan pada putaran mesin tinggi.",
        status: "NOMINAL CLEARANCE"
    },
    groove: {
        title: "ALUR KUKU KLEP (KEEPER / LOCK GROOVE)",
        spec: "Tipe: Single / Triple Radial Groove • Induction Hardened",
        detail: "Titik penguncian retainer pegas klep. Mengalami proses induksi panas untuk kekerasan maksimal (50+ HRC) guna mencegah keausan akibat getaran kecepatan tinggi.",
        status: "LOCK TOLERANCE PASS"
    },
    packaging: {
        title: "STANDAR PACKAGING FNI (RUST PREVENTIVE)",
        spec: "Seal: VCI Anti-Corrosion Film • Barcode Traceability",
        detail: "Setiap batch katup dilapisi pelindung anti-korosi VCI dan diinspeksi kuantitasnya sebelum dimasukkan ke dalam blister/box dengan label barcode resmi FNI.",
        status: "READY FOR DISPATCH"
    }
};

function selectValveHotspot(key) {
    const data = valveHotspotData[key];
    if (!data) return;

    document.querySelectorAll('.valve-hotspot-pin').forEach(pin => {
        pin.classList.toggle('active', pin.dataset.hotspot === key);
    });

    const titleEl = document.getElementById('hotspotTitle');
    const specEl = document.getElementById('hotspotSpec');
    const detailEl = document.getElementById('hotspotDetail');
    const statusEl = document.getElementById('hotspotStatus');

    if (titleEl) titleEl.innerText = data.title;
    if (specEl) specEl.innerText = data.spec;
    if (detailEl) detailEl.innerText = data.detail;
    if (statusEl) statusEl.innerText = data.status;
}

// ==================== DOM READY INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', () => {
    initDashboardLiveFilters();
    initOperatorQC();

    document.querySelectorAll('.valve-hotspot-pin').forEach(pin => {
        pin.addEventListener('click', () => {
            selectValveHotspot(pin.dataset.hotspot);
        });
    });
});
