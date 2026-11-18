<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT Federal Nittan Industries — Monitoring Packing Valve</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/valve-logo.svg') }}">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #09111f;
            color: #e2e8f0;
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
            position: relative;
            overflow: hidden;
        }

        /* Subtle background glow */
        body::before {
            content: '';
            position: fixed;
            top: -30%;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 500px;
            background: radial-gradient(ellipse, rgba(14, 100, 180, 0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ---- Wrapper ---- */
        .page-wrap {
            width: 100%;
            max-width: 460px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }

        /* ---- Logo + Brand ---- */
        .brand-block {
            text-align: center;
        }
        .brand-logo-wrap {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(2, 132, 199, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        .brand-logo-wrap img {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }
        .brand-company {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 0.4rem;
        }
        .brand-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #f1f5f9;
            letter-spacing: -0.03em;
            line-height: 1.25;
        }
        .brand-title span {
            color: #38bdf8;
        }

        /* ---- Divider ---- */
        .line-divider {
            width: 100%;
            height: 1px;
            background: rgba(255,255,255,0.07);
        }

        /* ---- Label above cards ---- */
        .access-label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #475569;
            align-self: flex-start;
        }

        /* ---- Role Cards ---- */
        .cards-stack {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .role-card {
            width: 100%;
            background: #0f1c35;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 1.25rem 1.4rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.22s ease;
            cursor: pointer;
        }
        .role-card:hover {
            background: #152242;
            border-color: rgba(56, 189, 248, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 10px 28px -8px rgba(2, 100, 180, 0.3);
            color: inherit;
        }
        .role-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .icon-spv {
            background: rgba(2, 132, 199, 0.12);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.2);
        }
        .icon-opr {
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.2);
        }
        .role-info {
            flex: 1;
            min-width: 0;
        }
        .role-name {
            font-size: 0.98rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 2px;
        }
        .role-hint {
            font-size: 0.8rem;
            color: #64748b;
        }
        .role-arrow {
            color: #334155;
            font-size: 1rem;
            flex-shrink: 0;
            transition: color 0.2s, transform 0.2s;
        }
        .role-card:hover .role-arrow {
            color: #38bdf8;
            transform: translateX(3px);
        }

        /* ---- Footer ---- */
        .landing-footer {
            font-size: 0.75rem;
            color: #334155;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="page-wrap">

        <!-- Brand -->
        <div class="brand-block">
            <div class="brand-logo-wrap">
                <img src="{{ asset('images/valve-logo.svg') }}" alt="FNI">
            </div>
            <div class="brand-company">PT Federal Nittan Industries</div>
            <div class="brand-title">Monitoring<br><span>Packing Valve</span></div>
        </div>

        <div class="line-divider"></div>

        <div class="access-label">Pilih Akses Masuk</div>

        <!-- Role Cards -->
        <div class="cards-stack">
            <a href="{{ route('login', ['role' => 'supervisor']) }}" class="role-card">
                <div class="role-icon icon-spv">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="role-info">
                    <div class="role-name">Supervisor</div>
                    <div class="role-hint">Penugasan, monitoring & surat jalan</div>
                </div>
                <i class="bi bi-chevron-right role-arrow"></i>
            </a>

            <a href="{{ route('login', ['role' => 'operator']) }}" class="role-card">
                <div class="role-icon icon-opr">
                    <i class="bi bi-person-gear"></i>
                </div>
                <div class="role-info">
                    <div class="role-name">Operator</div>
                    <div class="role-hint">ACC tugas, progres packing & riwayat</div>
                </div>
                <i class="bi bi-chevron-right role-arrow"></i>
            </a>
        </div>

        <!-- Footer -->
        <div class="landing-footer">
            &copy; {{ date('Y') }} PT Federal Nittan Industries
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
