<!DOCTYPE html>
<html lang="id" data-bs-theme="dark" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Dialihkan | FNI Packaging Telemetry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/valve-logo.svg') }}">
    <style>
        body {
            background: #0b1120;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .error-card {
            background: rgba(24, 34, 52, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            max-width: 520px;
            width: 100%;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(12px);
        }
        .error-code {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            font-family: 'JetBrains Mono', monospace;
        }
        .error-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(2, 132, 199, 0.15);
            color: #38bdf8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
        <div class="error-code">403</div>
        <h4 class="fw-bold text-white mb-2">Akses Memerlukan Autentikasi</h4>
        <p class="text-secondary small mb-4" style="color: #94a3b8 !important;">
            Sesi akun Anda telah diperbarui atau halaman ini memerlukan hak akses yang sesuai. Anda tidak perlu menulis ulang alamat localhost dari awal.
        </p>

        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ url('/') }}" class="btn btn-cyber">
                <i class="bi bi-house-door-fill me-1"></i> Ke Halaman Utama
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light" style="border-radius: 8px;">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login Ulang
            </a>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-10">
            <small class="text-muted" style="font-size: 0.75rem;">
                Mengalihkan otomatis ke halaman login dalam <span id="countdownTimer" class="fw-bold text-white">5</span> detik...
            </small>
        </div>
    </div>

    <script>
        let countdown = 5;
        const timerEl = document.getElementById('countdownTimer');
        const interval = setInterval(() => {
            countdown--;
            if (timerEl) timerEl.innerText = countdown;
            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('login') }}";
            }
        }, 1000);
    </script>
</body>
</html>
