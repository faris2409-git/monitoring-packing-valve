<!DOCTYPE html>
<html lang="id" data-bs-theme="dark" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Monitoring Packing Valve') - FNI Telemetry</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}?v={{ file_exists(public_path('css/custom.css')) ? filemtime(public_path('css/custom.css')) : time() }}" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/valve-logo.svg') }}">

    <script>
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        const savedSidebar = localStorage.getItem('fni_sidebar');
        if (savedSidebar === 'collapsed') {
            document.addEventListener('DOMContentLoaded', () => {
                document.body.classList.add('sidebar-collapsed');
            });
        }
    </script>
</head>
<body class="min-vh-100">
    @auth
        @php
            $user = auth()->user();
            $navAssignedCount = $user->role === 'operator'
                ? \App\Models\PackagingTask::where('assigned_to', $user->id)->where('status', 'assigned')->count()
                : 0;
            $navInProgressCount = $user->role === 'operator'
                ? \App\Models\PackagingTask::where('assigned_to', $user->id)->whereIn('status', ['accepted', 'in_progress'])->count()
                : 0;
            $navSupervisorActiveCount = $user->role === 'supervisor'
                ? \App\Models\PackagingTask::whereIn('status', ['assigned', 'accepted', 'in_progress'])->count()
                : 0;
        @endphp

        <div class="app-shell">
            <!-- ==================== DESKTOP FIXED SIDEBAR ==================== -->
            <aside class="app-sidebar">
                <!-- Brand Header -->
                <div class="app-sidebar-header">
                    <a class="sidebar-brand" href="{{ route('landing') }}">
                        <img src="{{ asset('images/valve-logo.svg') }}" alt="Valve Logo" class="sidebar-brand-logo">
                        <div class="d-flex flex-column">
                            <span class="sidebar-brand-title">
                                FNI <span style="color: var(--accent);">//</span> VALVE
                            </span>
                            <span class="sidebar-brand-tag">
                                PACKAGING SYSTEM
                            </span>
                        </div>
                    </a>
                </div>

                <!-- User Mini Profile -->
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="sidebar-user-meta">
                        <div class="sidebar-user-name" title="{{ $user->name }}">{{ $user->name }}</div>
                        <div class="sidebar-user-role">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.45rem; color: var(--status-online);"></i>
                            {{ strtoupper($user->role) }}
                            @if($user->role === 'operator')
                                <span class="badge ms-1" style="font-size: 0.6rem; background: rgba(56, 189, 248, 0.2); color: #38bdf8;">
                                    {{ session('active_shift') ?? ($user->shift ?? 'Shift 1') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Scrollable Navigation Tree -->
                <div class="app-sidebar-body">
                    @if($user->role === 'supervisor')
                        <!-- Supervisor: Main -->
                        <div class="sidebar-section-title">Ringkasan</div>
                        <ul class="sidebar-nav-list">
                            <li>
                                <a href="{{ route('supervisor.dashboard') }}" class="sidebar-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-grid-1x2-fill"></i></span>
                                    <span class="sidebar-link-label">Command Center</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Supervisor: Operations -->
                        <div class="sidebar-section-title">Operasional</div>
                        <ul class="sidebar-nav-list">
                            <li>
                                <a href="{{ route('supervisor.packaging-tasks.index') }}" class="sidebar-link {{ request()->routeIs('supervisor.packaging-tasks.*') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-clipboard-check-fill"></i></span>
                                    <span class="sidebar-link-label">Penugasan & Monitoring</span>
                                    @if($navSupervisorActiveCount > 0)
                                        <span class="sidebar-badge ms-auto">{{ $navSupervisorActiveCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supervisor.engine-valves.index') }}" class="sidebar-link {{ request()->routeIs('supervisor.engine-valves.*') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-gear-wide-connected"></i></span>
                                    <span class="sidebar-link-label">Data Engine Valve</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supervisor.shipments.index') }}" class="sidebar-link {{ request()->routeIs('supervisor.shipments.*') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-truck"></i></span>
                                    <span class="sidebar-link-label">Surat Jalan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supervisor.operators.index') }}" class="sidebar-link {{ request()->routeIs('supervisor.operators.*') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-people-fill"></i></span>
                                    <span class="sidebar-link-label">Daftar Operator</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Supervisor: Quick Actions -->
                        <div class="sidebar-section-title">Pintasan Cepat</div>
                        <ul class="sidebar-nav-list">
                            <li>
                                <a href="{{ route('supervisor.packaging-tasks.create') }}" class="sidebar-link {{ request()->routeIs('supervisor.packaging-tasks.create') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-plus-circle text-accent"></i></span>
                                    <span class="sidebar-link-label">Buat Penugasan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supervisor.engine-valves.create') }}" class="sidebar-link {{ request()->routeIs('supervisor.engine-valves.create') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-plus-square text-accent"></i></span>
                                    <span class="sidebar-link-label">Tambah Valve Baru</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supervisor.shipments.create') }}" class="sidebar-link {{ request()->routeIs('supervisor.shipments.create') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-file-earmark-plus text-accent"></i></span>
                                    <span class="sidebar-link-label">Buat Surat Jalan</span>
                                </a>
                            </li>
                        </ul>

                    @elseif($user->role === 'operator')
                        <!-- Operator: Main -->
                        <div class="sidebar-section-title">Ringkasan</div>
                        <ul class="sidebar-nav-list">
                            <li>
                                <a href="{{ route('operator.dashboard') }}" class="sidebar-link {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-speedometer2"></i></span>
                                    <span class="sidebar-link-label">Workstation Utama</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Operator: Stations -->
                        <div class="sidebar-section-title">Stasiun Kerja</div>
                        <ul class="sidebar-nav-list">
                            <li>
                                <a href="{{ route('operator.packaging-tasks.index') }}" class="sidebar-link {{ request()->routeIs('operator.packaging-tasks.index') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-inbox-fill"></i></span>
                                    <span class="sidebar-link-label">Tugas Baru (ACC)</span>
                                    @if($navAssignedCount > 0)
                                        <span class="sidebar-badge badge-danger ms-auto">{{ $navAssignedCount }} Baru</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('operator.packaging-tasks.work') }}" class="sidebar-link {{ request()->routeIs('operator.packaging-tasks.work') || request()->routeIs('operator.packaging-tasks.edit') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-tools"></i></span>
                                    <span class="sidebar-link-label">Pengerjaan Aktif</span>
                                    @if($navInProgressCount > 0)
                                        <span class="sidebar-badge ms-auto">{{ $navInProgressCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('operator.packaging-tasks.history') }}" class="sidebar-link {{ request()->routeIs('operator.packaging-tasks.history') ? 'active' : '' }}">
                                    <span class="sidebar-link-icon"><i class="bi bi-clock-history"></i></span>
                                    <span class="sidebar-link-label">Riwayat Selesai</span>
                                </a>
                            </li>
                        </ul>
                    @endif
                </div>

                <!-- Telemetry Status & Logout -->
                <div class="app-sidebar-footer">
                    <div class="sidebar-telemetry-badge">
                        <span class="d-flex align-items-center gap-1 text-muted">
                            <span class="pulse-dot"></span> STATION FNI-01
                        </span>
                        <span class="text-accent fw-bold">ONLINE</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="sidebar-logout-btn">
                            <i class="bi bi-box-arrow-right"></i> <span>Keluar dari Sesi</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- ==================== MOBILE OFFCANVAS DRAWER ==================== -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebarDrawer" style="background: var(--surface-solid); border-right: 1px solid var(--border); width: 280px;">
                <div class="offcanvas-header border-bottom border-secondary border-opacity-25 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('images/valve-logo.svg') }}" alt="Valve Logo" style="width: 32px; height: 32px;">
                        <div>
                            <div class="fw-bold text-white small">FNI // VALVE</div>
                            <div class="text-muted" style="font-size: 0.68rem;">PACKAGING TELEMETRY</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
                    <div>
                        <div class="p-3 rounded-3 mb-3" style="background: var(--surface-subtle); border: 1px solid var(--border);">
                            <div class="fw-bold text-white small">{{ $user->name }}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">{{ $user->email }}</div>
                            <span class="badge mt-2" style="background: rgba(var(--accent-rgb), 0.15); color: var(--accent); font-size: 0.68rem;">
                                {{ strtoupper($user->role) }}
                                @if($user->role === 'operator')
                                    • {{ session('active_shift') ?? ($user->shift ?? 'Shift 1') }}
                                    @if($user->npk) (NPK: {{ $user->npk }}) @endif
                                @endif
                            </span>
                        </div>

                        @if($user->role === 'supervisor')
                            <div class="text-muted small fw-bold text-uppercase px-1 mb-2" style="font-size: 0.68rem;">Navigasi</div>
                            <div class="d-flex flex-column gap-1 mb-3">
                                <a href="{{ route('supervisor.dashboard') }}" class="sidebar-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
                                    <i class="bi bi-grid-1x2-fill sidebar-link-icon"></i> <span>Dashboard</span>
                                </a>
                                <a href="{{ route('supervisor.packaging-tasks.index') }}" class="sidebar-link {{ request()->routeIs('supervisor.packaging-tasks.*') ? 'active' : '' }}">
                                    <i class="bi bi-clipboard-check-fill sidebar-link-icon"></i> <span>Penugasan & Monitoring</span>
                                    @if($navSupervisorActiveCount > 0)
                                        <span class="sidebar-badge ms-auto">{{ $navSupervisorActiveCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('supervisor.engine-valves.index') }}" class="sidebar-link {{ request()->routeIs('supervisor.engine-valves.*') ? 'active' : '' }}">
                                    <i class="bi bi-gear-wide-connected sidebar-link-icon"></i> <span>Data Valve</span>
                                </a>
                                <a href="{{ route('supervisor.shipments.index') }}" class="sidebar-link {{ request()->routeIs('supervisor.shipments.*') ? 'active' : '' }}">
                                    <i class="bi bi-truck sidebar-link-icon"></i> <span>Surat Jalan</span>
                                </a>
                                <a href="{{ route('supervisor.operators.index') }}" class="sidebar-link {{ request()->routeIs('supervisor.operators.*') ? 'active' : '' }}">
                                    <i class="bi bi-people-fill sidebar-link-icon"></i> <span>Daftar Operator</span>
                                </a>
                            </div>

                            <div class="text-muted small fw-bold text-uppercase px-1 mb-2" style="font-size: 0.68rem;">Pintasan Cepat</div>
                            <div class="d-flex flex-column gap-1">
                                <a href="{{ route('supervisor.packaging-tasks.create') }}" class="sidebar-link">
                                    <i class="bi bi-plus-circle text-accent sidebar-link-icon"></i> <span>Buat Penugasan</span>
                                </a>
                                <a href="{{ route('supervisor.engine-valves.create') }}" class="sidebar-link">
                                    <i class="bi bi-plus-square text-accent sidebar-link-icon"></i> <span>Tambah Valve</span>
                                </a>
                                <a href="{{ route('supervisor.shipments.create') }}" class="sidebar-link">
                                    <i class="bi bi-file-earmark-plus text-accent sidebar-link-icon"></i> <span>Buat Surat Jalan</span>
                                </a>
                            </div>
                        @else
                            <div class="text-muted small fw-bold text-uppercase px-1 mb-2" style="font-size: 0.68rem;">Workstation</div>
                            <div class="d-flex flex-column gap-1">
                                <a href="{{ route('operator.dashboard') }}" class="sidebar-link {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
                                    <i class="bi bi-speedometer2 sidebar-link-icon"></i> <span>Dashboard</span>
                                </a>
                                <a href="{{ route('operator.packaging-tasks.index') }}" class="sidebar-link {{ request()->routeIs('operator.packaging-tasks.index') ? 'active' : '' }}">
                                    <i class="bi bi-inbox-fill sidebar-link-icon"></i> <span>Tugas Baru (ACC)</span>
                                    @if($navAssignedCount > 0)
                                        <span class="sidebar-badge badge-danger ms-auto">{{ $navAssignedCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('operator.packaging-tasks.work') }}" class="sidebar-link {{ request()->routeIs('operator.packaging-tasks.work') ? 'active' : '' }}">
                                    <i class="bi bi-tools sidebar-link-icon"></i> <span>Pengerjaan Aktif</span>
                                    @if($navInProgressCount > 0)
                                        <span class="sidebar-badge ms-auto">{{ $navInProgressCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('operator.packaging-tasks.history') }}" class="sidebar-link {{ request()->routeIs('operator.packaging-tasks.history') ? 'active' : '' }}">
                                    <i class="bi bi-clock-history sidebar-link-icon"></i> <span>Riwayat Selesai</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-top border-secondary border-opacity-25 mt-4">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2" type="submit">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==================== MAIN CONTENT WRAPPER ==================== -->
            <div class="app-main-wrapper">
                <!-- Sleek App Topbar -->
                <header class="app-topbar">
                    <div class="app-topbar-left">
                        <!-- Mobile Hamburger Button -->
                        <button class="topbar-toggle-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebarDrawer" aria-label="Buka Menu">
                            <i class="bi bi-list fs-5"></i>
                        </button>

                        <!-- Desktop Sidebar Collapse Toggle Button -->
                        <button class="topbar-toggle-btn d-none d-lg-inline-flex" type="button" onclick="toggleSidebarCollapse()" title="Perlebar / Ciutkan Sidebar">
                            <i class="bi bi-layout-sidebar-inset fs-6"></i>
                        </button>

                        <!-- Page Title Indicator -->
                        <span class="topbar-breadcrumb d-none d-sm-flex breadcrumb-current fw-semibold">@yield('title', 'Dashboard')</span>
                    </div>

                    <!-- Right Controls Cluster -->
                    <div class="app-topbar-right">
                        <!-- Operator Active Shift Badge -->
                        @if($user->role === 'operator')
                            <div class="hud-telemetry-chip d-none d-sm-inline-flex" style="border-color: rgba(56, 189, 248, 0.35); background: rgba(56, 189, 248, 0.1);" title="Shift Kerja Aktif Operator">
                                <i class="bi bi-person-badge text-info me-1"></i>
                                <span class="text-info fw-semibold">{{ session('active_shift') ?? ($user->shift ?? 'Shift 1') }}</span>
                                @if($user->npk)
                                    <span class="text-muted ms-1 mono-font" style="font-size: 0.72rem;">[{{ $user->npk }}]</span>
                                @endif
                            </div>
                        @endif

                        <!-- Quick Profile Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-soft-action d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem; background: linear-gradient(135deg, var(--accent), var(--accent-secondary)); color: #000 !important;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="d-none d-md-inline fw-semibold small">{{ $user->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2" style="background: var(--surface-solid); border: 1px solid var(--border) !important; min-width: 220px; border-radius: 12px;">
                                <li class="px-3 py-2 border-bottom border-secondary border-opacity-25 mb-1">
                                    <div class="fw-bold text-white small">{{ $user->name }}</div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">{{ $user->email }}</div>
                                    <div class="mt-1 d-flex align-items-center gap-1 flex-wrap">
                                        <span class="badge" style="background: rgba(var(--accent-rgb), 0.15); color: var(--accent); font-size: 0.68rem;">
                                            ROLE: {{ strtoupper($user->role) }}
                                        </span>
                                        @if($user->role === 'operator')
                                            <span class="badge" style="background: rgba(56, 189, 248, 0.2); color: #38bdf8; font-size: 0.68rem;">
                                                {{ session('active_shift') ?? ($user->shift ?? 'Shift 1') }}
                                            </span>
                                            @if($user->npk)
                                                <span class="badge" style="background: rgba(255, 255, 255, 0.08); color: #cbd5e1; font-size: 0.68rem;">
                                                    NPK: {{ $user->npk }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </li>
                                @if($user->role === 'supervisor')
                                    <li>
                                        <a class="dropdown-item rounded py-2 small" href="{{ route('supervisor.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2 text-accent"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item rounded py-2 small" href="{{ route('supervisor.packaging-tasks.create') }}">
                                            <i class="bi bi-plus-circle me-2 text-accent"></i> Buat Penugasan
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item rounded py-2 small" href="{{ route('operator.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2 text-accent"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item rounded py-2 small" href="{{ route('operator.packaging-tasks.index') }}">
                                            <i class="bi bi-inbox me-2 text-accent"></i> Cek Tugas Baru
                                        </a>
                                    </li>
                                @endif
                                <li class="border-top border-secondary border-opacity-25 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button class="dropdown-item rounded py-2 small text-danger d-flex align-items-center gap-2" type="submit">
                                            <i class="bi bi-box-arrow-right"></i> Keluar dari Sesi
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>

                <!-- Page Content Body -->
                <main class="app-page-content fade-in">
                    @if (session('success'))
                        <div class="cyber-card p-3 mb-4 d-flex align-items-center justify-content-between" style="border-left: 4px solid var(--status-online); background: rgba(16, 185, 129, 0.1);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill fs-5" style="color: var(--status-online);"></i>
                                <span class="text-white small fw-medium">{{ session('success') }}</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="cyber-card p-3 mb-4 d-flex align-items-center justify-content-between" style="border-left: 4px solid var(--status-danger); background: rgba(239, 68, 68, 0.1);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-octagon-fill text-danger fs-5"></i>
                                <span class="text-white small fw-medium">{{ session('error') }}</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="cyber-ticker mt-auto">
                    <div class="container-fluid d-flex justify-content-end align-items-center">
                        <div class="text-muted small">
                            &copy; {{ date('Y') }} PT Federal Nittan Industries
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    @else
        <!-- Guest View (Fallback if layout extended without auth) -->
        <div class="container py-4 flex-grow-1">
            @yield('content')
        </div>
    @endauth

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/valve-interactive.js') }}?v={{ file_exists(public_path('js/valve-interactive.js')) ? filemtime(public_path('js/valve-interactive.js')) : time() }}"></script>
    <script>
        function toggleSidebarCollapse() {
            document.body.classList.toggle('sidebar-collapsed');
            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('fni_sidebar', isCollapsed ? 'collapsed' : 'expanded');
            if (window.cyberSound) {
                window.cyberSound.playClick();
            }
        }
    </script>

    @yield('scripts')
</body>
</html>