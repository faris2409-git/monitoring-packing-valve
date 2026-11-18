<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - PT Federal Nittan Industries</title>
    
    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/valve-realistic.svg') }}?v={{ time() }}">

    <style>
        :root {
            --bg-page: #f8fafc;
            --bg-radial: radial-gradient(circle at 50% -20%, rgba(2, 132, 199, 0.09) 0%, rgba(248, 250, 252, 0) 70%);
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --card-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.06), 0 1px 3px rgba(15, 23, 42, 0.03);
            
            --text-heading: #0f172a;
            --text-sub: #64748b;
            --text-body: #1e293b;
            --text-label: #334155;
            
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-text: #0f172a;
            --input-icon: #94a3b8;
            --input-focus: #0284c7;
            --input-focus-ring: rgba(2, 132, 199, 0.12);
            
            --pill-bg: #f1f5f9;
            --pill-border: #e2e8f0;
            --pill-text: #64748b;
            --pill-active-bg: #0284c7;
            
            --btn-primary: linear-gradient(135deg, #0284c7, #0369a1);
            --btn-primary-hover: linear-gradient(135deg, #0369a1, #075985);
            --btn-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }

        [data-theme="dark"] {
            --bg-page: #0b1329;
            --bg-radial: radial-gradient(circle at 50% -20%, rgba(2, 132, 199, 0.16) 0%, rgba(11, 19, 41, 0) 70%);
            --card-bg: #141e36;
            --card-border: rgba(255, 255, 255, 0.08);
            --card-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            
            --text-heading: #ffffff;
            --text-sub: #94a3b8;
            --text-body: #e2e8f0;
            --text-label: #cbd5e1;
            
            --input-bg: #0c1427;
            --input-border: rgba(255, 255, 255, 0.12);
            --input-text: #ffffff;
            --input-icon: #64748b;
            --input-focus: #38bdf8;
            --input-focus-ring: rgba(56, 189, 248, 0.16);
            
            --pill-bg: #0c1427;
            --pill-border: rgba(255, 255, 255, 0.08);
            --pill-text: #94a3b8;
            --pill-active-bg: #0284c7;
            
            --btn-primary: linear-gradient(135deg, #0284c7, #0369a1);
            --btn-primary-hover: linear-gradient(135deg, #0369a1, #075985);
            --btn-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-page);
            background-image: var(--bg-radial);
            background-attachment: fixed;
            color: var(--text-body);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: background-color 0.25s ease, color 0.25s ease;
            margin: 0;
            padding: 0;
        }

        /* Top Minimal Bar */
        .top-action-bar {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .btn-ghost-top {
            color: var(--text-sub);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--card-border);
        }
        .btn-ghost-top:hover {
            color: var(--text-heading);
            background: rgba(2, 132, 199, 0.12);
            border-color: var(--input-focus);
        }

        /* Main Floating Card */
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            margin: auto;
            padding: 1.5rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 100%;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 2.25rem 2rem;
            position: relative;
            transition: all 0.25s ease;
        }

        /* Brand Header */
        .brand-badge {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: rgba(2, 132, 199, 0.08);
            border: 1px solid rgba(2, 132, 199, 0.15);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
        }
        .brand-badge img {
            width: 30px;
            height: 30px;
        }

        .login-title {
            color: var(--text-heading);
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
        }

        .login-slogan {
            color: var(--text-sub);
            font-size: 0.86rem;
            margin: 0;
        }

        /* Segmented Role Switch */
        .role-switch-container {
            background-color: var(--pill-bg);
            border: 1px solid var(--pill-border);
            border-radius: 12px;
            padding: 4px;
            display: flex;
            gap: 4px;
            margin-bottom: 1.5rem;
        }

        .role-pill {
            flex: 1;
            padding: 0.52rem 0.75rem;
            border-radius: 9px;
            font-size: 0.86rem;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            color: var(--pill-text);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: none;
        }

        .role-pill:hover {
            color: var(--text-heading);
        }

        .role-pill.active {
            background: var(--pill-active-bg);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.32);
        }

        /* Form & Input Groups with Left Icons */
        .form-label {
            color: var(--text-label);
            font-size: 0.83rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .input-icon-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-group .field-icon {
            position: absolute;
            left: 14px;
            color: var(--input-icon);
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.2s ease;
            z-index: 2;
        }

        .input-icon-group .form-control,
        .input-icon-group .form-select {
            background-color: var(--input-bg);
            border: 1.5px solid var(--input-border);
            color: var(--input-text);
            border-radius: 10px;
            padding: 0.65rem 0.85rem 0.65rem 42px;
            font-size: 0.92rem;
            font-family: inherit;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
            width: 100%;
        }

        .input-icon-group:focus-within .field-icon {
            color: var(--input-focus);
        }

        .input-icon-group .form-control:focus,
        .input-icon-group .form-select:focus {
            background-color: var(--input-bg);
            border-color: var(--input-focus);
            color: var(--input-text);
            box-shadow: 0 0 0 3px var(--input-focus-ring);
            outline: none;
        }

        .input-icon-group .form-control::placeholder {
            color: var(--text-sub);
            opacity: 0.55;
            font-size: 0.88rem;
        }

        /* Password Eye Toggle */
        .btn-toggle-pwd {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            color: var(--input-icon);
            cursor: pointer;
            padding: 6px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
            z-index: 2;
            border-radius: 6px;
        }
        .btn-toggle-pwd:hover {
            color: var(--text-heading);
        }

        /* Submit Button */
        .btn-submit {
            background: var(--btn-primary);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 0.72rem 1rem;
            font-size: 0.94rem;
            font-weight: 600;
            width: 100%;
            box-shadow: var(--btn-shadow);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-submit:hover {
            background: var(--btn-primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
        }
        .btn-submit:active {
            transform: translateY(0);
        }

        .footer-text {
            color: var(--text-sub);
            font-size: 0.78rem;
            padding: 1.25rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Top Action Bar -->
    <div class="top-action-bar">
        <a href="{{ route('landing') }}" class="btn-ghost-top">
            <i class="bi bi-arrow-left"></i> <span>Beranda</span>
        </a>
    </div>

    <!-- Main Container -->
    <div class="login-wrapper">
        <div class="login-card">
            
            <!-- Brand Header -->
            <div class="text-center mb-4">
                <div class="brand-badge">
                    <img src="{{ asset('images/valve-realistic.svg') }}?v={{ time() }}" alt="Valve Logo">
                </div>
                
                <h1 class="login-title">PT Federal Nittan Industries</h1>
                <p class="login-slogan">Jaminan packing berkualitas</p>
            </div>

            <!-- Role Selector Tabs -->
            <div class="role-switch-container">
                <a href="{{ route('login', ['role' => 'supervisor']) }}" 
                   class="role-pill {{ $role === 'supervisor' ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> Supervisor
                </a>
                <a href="{{ route('login', ['role' => 'operator']) }}" 
                   class="role-pill {{ $role === 'operator' ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> Operator
                </a>
            </div>

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger border-0 p-3 mb-3 small rounded-3" style="background: rgba(239, 68, 68, 0.12); color: #dc2626; border-left: 3px solid #ef4444 !important;">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">

                @if($role === 'operator')
                    <!-- Operator: Username -->
                    <div class="mb-3">
                        <label class="form-label" for="usernameInput">Username</label>
                        <div class="input-icon-group">
                            <i class="bi bi-person field-icon"></i>
                            <input type="text" id="usernameInput" name="username" class="form-control" 
                                   placeholder="Username operator" value="{{ old('username') }}" required autofocus>
                        </div>
                    </div>

                    <!-- Operator: NPK -->
                    <div class="mb-3">
                        <label class="form-label" for="npkInput">NPK</label>
                        <div class="input-icon-group">
                            <i class="bi bi-card-heading field-icon"></i>
                            <input type="text" id="npkInput" name="npk" class="form-control" 
                                   placeholder="Contoh: OP-001" value="{{ old('npk') }}" required>
                        </div>
                    </div>

                    <!-- Operator: Shift -->
                    <div class="mb-3">
                        <label class="form-label" for="shiftSelect">Shift Kerja</label>
                        <div class="input-icon-group">
                            <i class="bi bi-clock field-icon"></i>
                            <select id="shiftSelect" name="shift" class="form-select" required>
                                <option value="Shift 1 (07:00 - 15:30)" {{ old('shift') == 'Shift 1 (07:00 - 15:30)' ? 'selected' : '' }}>Shift 1 (07:00 - 15:30 WIB)</option>
                                <option value="Shift 2 (15:30 - 23:00)" {{ old('shift') == 'Shift 2 (15:30 - 23:00)' ? 'selected' : '' }}>Shift 2 (15:30 - 23:00 WIB)</option>
                                <option value="Shift 3 (23:00 - 07:00)" {{ old('shift') == 'Shift 3 (23:00 - 07:00)' ? 'selected' : '' }}>Shift 3 (23:00 - 07:00 WIB)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Operator: Password -->
                    <div class="mb-3">
                        <label class="form-label" for="passwordInput">Password</label>
                        <div class="input-icon-group">
                            <i class="bi bi-lock field-icon"></i>
                            <input type="password" id="passwordInput" name="password" class="form-control pe-5" placeholder="••••••••" required>
                            <button type="button" class="btn-toggle-pwd" onclick="togglePasswordVisibility()" aria-label="Lihat password">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Supervisor: Email / Username -->
                    <div class="mb-3">
                        <label class="form-label" for="loginIdInput">Email atau Username</label>
                        <div class="input-icon-group">
                            <i class="bi bi-envelope field-icon"></i>
                            <input type="text" id="loginIdInput" name="login_id" class="form-control" 
                                   placeholder="supervisor@fni.com" value="{{ old('login_id', old('email')) }}" required autofocus>
                        </div>
                    </div>

                    <!-- Supervisor: Password -->
                    <div class="mb-3">
                        <label class="form-label" for="passwordInput">Password</label>
                        <div class="input-icon-group">
                            <i class="bi bi-lock field-icon"></i>
                            <input type="password" id="passwordInput" name="password" class="form-control pe-5" placeholder="••••••••" required>
                            <button type="button" class="btn-toggle-pwd" onclick="togglePasswordVisibility()" aria-label="Lihat password">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <button type="submit" class="btn-submit mt-4">
                    <span>Masuk ke Sistem</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>



        </div>
    </div>

    <!-- Clean Simple Footer -->
    <div class="footer-text">
        &copy; {{ date('Y') }} PT Federal Nittan Industries
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('passwordInput');
            const icon = document.getElementById('togglePasswordIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                passInput.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

    
    </script>
</body>
</html>