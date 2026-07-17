<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tracer Study BPVP Sidoarjo')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
    
    <!-- Skrip Deteksi Awal Tema (Anti-Flicker) - HANYA UNTUK DASHBOARD -->
    @if(trim($__env->yieldContent('layout', 'dashboard')) !== 'auth')
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'auto';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (theme === 'auto' && prefersDark)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    @else
    <script>
        // Kunci secara paksa halaman Login di Mode Terang
        document.documentElement.setAttribute('data-theme', 'light');
    </script>
    @endif

    <!-- CSS Wajib Dark Mode (diletakkan di master layout agar tidak terkena cache/build) -->
    @if(trim($__env->yieldContent('layout', 'dashboard')) !== 'auth')
    <style>
        /* ----------------------------------------------------
           DARK MODE OVERRIDES - Aktif saat data-theme="dark"
        ------------------------------------------------------- */
        html[data-theme="dark"] body {
            background-color: #121212 !important;
            color: #ffffff !important;
        }

        html[data-theme="dark"] .sidebar, 
        html[data-theme="dark"] .main-sidebar {
            background-color: #1e1e1e !important;
            border-right: 1px solid #333333 !important;
        }

        html[data-theme="dark"] .top-navbar,
        html[data-theme="dark"] .navbar {
            background-color: #1e1e1e !important;
            border-bottom: 1px solid #333333 !important;
        }

        html[data-theme="dark"] .card, 
        html[data-theme="dark"] .info-box {
            background-color: #1e1e1e !important;
            border-color: #333333 !important;
        }
        
        html[data-theme="dark"] .card-header {
            border-bottom-color: #333333 !important;
        }

        html[data-theme="dark"] .text-dark,
        html[data-theme="dark"] .card-title,
        html[data-theme="dark"] h1, 
        html[data-theme="dark"] h2, 
        html[data-theme="dark"] h3, 
        html[data-theme="dark"] h4, 
        html[data-theme="dark"] h5, 
        html[data-theme="dark"] h6 {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .text-gray,
        html[data-theme="dark"] .text-muted {
            color: #a0a0a0 !important;
        }

        html[data-theme="dark"] .table th {
            background-color: #121212 !important;
            color: #a0a0a0 !important;
            border-color: #333333 !important;
        }

        html[data-theme="dark"] .table td {
            border-color: #333333 !important;
            color: #ffffff !important;
        }

        html[data-theme="dark"] .table tbody tr:hover {
            background-color: #1a1a1a !important;
        }

        html[data-theme="dark"] .form-input, 
        html[data-theme="dark"] select.form-input {
            background-color: #121212 !important;
            border-color: #333333 !important;
            color: #ffffff !important;
        }

        html[data-theme="dark"] .form-label {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .stat-card {
            background-color: #1e1e1e !important;
            border-color: #333333 !important;
        }

        html[data-theme="dark"] .stat-info .value {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .btn-outline {
            color: #ffffff !important;
            border-color: #333333 !important;
        }

        html[data-theme="dark"] .btn-outline:hover {
            background-color: #333333 !important;
        }

        html[data-theme="dark"] .action-btn {
            background-color: #121212 !important;
            color: #ffffff !important;
        }

        /* Kustomisasi Dropdown UI Theme Switcher di Mode Gelap */
        html[data-theme="dark"] .dropdown-menu {
            background-color: #1e1e1e !important;
            border-color: #333333 !important;
        }
        html[data-theme="dark"] .dropdown-item {
            color: #ffffff !important;
        }
        html[data-theme="dark"] .dropdown-item:hover,
        html[data-theme="dark"] .dropdown-item:focus {
            background-color: #333333 !important;
        }
        html[data-theme="dark"] .dropdown-item.active {
            background-color: var(--primary) !important;
            color: white !important;
        }
    </style>
    @endif
</head>
<body>
    
    @if(trim($__env->yieldContent('layout', 'dashboard')) === 'auth')
        @yield('content')
    @else
        <div class="app-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-header">
                    <i class="fas fa-graduation-cap" style="margin-right: 10px;"></i> Tracer Study
                </div>
                <div class="sidebar-menu">
                    @php
                        $isAdmin = false;
                        if (Auth::check()) {
                            $isAdmin = Auth::user()->role === 'Admin';
                        } else {
                            $isAdmin = session('role') === 'admin' || session('role') === 'Admin';
                        }
                    @endphp
                    @if($isAdmin)
                        <!-- Admin Menu -->
                        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.akun.index') }}" class="menu-item {{ request()->is('admin/akun*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog"></i> Kelola Akun
                        </a>
                        <a href="{{ route('admin.alumni.index') }}" class="menu-item {{ request()->is('admin/alumni*') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate"></i> Kelola Data Alumni
                        </a>
                        <a href="{{ route('admin.lowongan.index') }}" class="menu-item {{ request()->is('admin/lowongan*') ? 'active' : '' }}">
                            <i class="fas fa-briefcase"></i> Kelola Lowongan
                        </a>
                        <a href="{{ route('admin.kuesioner.index') }}" class="menu-item {{ request()->is('admin/kuesioner*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i> Kelola Kuesioner
                        </a>
                        <a href="{{ route('admin.laporan.index') }}" class="menu-item {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i> Kelola Laporan
                        </a>
                    @else
                        <!-- Alumni Menu -->
                        <a href="{{ route('alumni.dashboard') }}" class="menu-item {{ request()->is('alumni/dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a href="{{ route('alumni.kuesioner.index') }}" class="menu-item {{ request()->is('alumni/kuesioner*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check"></i> Isi Kuesioner
                        </a>
                        <a href="{{ route('alumni.lowongan.index') }}" class="menu-item {{ request()->is('alumni/lowongan*') ? 'active' : '' }}">
                            <i class="fas fa-briefcase"></i> Info Lowongan
                        </a>
                        <a href="{{ route('alumni.profil.index') }}" class="menu-item {{ request()->is('alumni/profil*') ? 'active' : '' }}">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </a>
                    @endif
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Top Navbar -->
                <header class="top-navbar">
                    <div class="flex items-center gap-4">
                        <h2 class="font-bold text-gray" style="font-size: 1.125rem;">@yield('page_title', 'Dashboard')</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Notification Bell (Khusus Admin) -->
                        @if(isset($isAdmin) && $isAdmin && isset($resetRequests))
                        <div style="position: relative;">
                            <button id="notifDropdownBtn" class="btn btn-outline" style="padding: 0; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; position: relative;">
                                <i class="fas fa-bell"></i>
                                @if($resetRequests->count() > 0)
                                    <span style="position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 0.65rem; font-weight: 700; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-sizing: content-box;">
                                        {{ $resetRequests->count() > 9 ? '9+' : $resetRequests->count() }}
                                    </span>
                                @endif
                            </button>
                            <!-- Dropdown Notifikasi -->
                            <div id="notifDropdownMenu" class="dropdown-menu shadow-md" style="display: none; position: absolute; right: 0; top: calc(100% + 0.5rem); width: 320px; background: white; border: 1px solid var(--gray-light); border-radius: var(--radius-md); z-index: 1000; overflow: hidden;">
                                <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-light); background: #f8fafc;">
                                    <span style="font-weight: 600; font-size: 0.875rem; color: var(--dark);">Notifikasi Reset Password</span>
                                </div>
                                <div style="max-height: 320px; overflow-y: auto; padding: 0.25rem 0;">
                                    @if($resetRequests->count() > 0)
                                        @foreach($resetRequests as $req)
                                            <a href="{{ route('admin.akun.index', ['search' => $req->username_nik]) }}" class="dropdown-item" style="display: flex; flex-direction: column; padding: 0.75rem 1rem; text-decoration: none; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                                <span style="font-weight: 600; color: var(--dark); font-size: 0.875rem; margin-bottom: 0.25rem;">{{ $req->nama_lengkap }}</span>
                                                <span style="color: #ef4444; font-size: 0.75rem; font-weight: 500;"><i class="fas fa-key" style="margin-right: 4px;"></i> Meminta Reset ({{ $req->username_nik }})</span>
                                            </a>
                                        @endforeach
                                    @else
                                        <div style="padding: 1.5rem 1rem; text-align: center; color: var(--gray); font-size: 0.875rem;">
                                            <i class="fas fa-check-circle" style="font-size: 2rem; color: #10b981; margin-bottom: 0.75rem; display: block;"></i>
                                            Tidak ada notifikasi baru
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Theme Switcher -->
                        <div style="position: relative;">
                            <button id="themeDropdownBtn" class="btn btn-outline" style="padding: 0; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                <i id="theme-icon-active" class="fas fa-circle-half-stroke"></i>
                            </button>
                            <ul id="themeDropdownMenu" class="dropdown-menu shadow-md" style="display: none; position: absolute; right: 0; top: calc(100% + 0.5rem); min-width: 150px; background: white; border: 1px solid var(--gray-light); border-radius: var(--radius-md); list-style: none; padding: 0.5rem 0; z-index: 1000;">
                                <li>
                                    <button type="button" class="dropdown-item flex items-center theme-switcher w-full" data-theme-value="light" style="padding: 0.5rem 1rem; background: none; border: none; cursor: pointer; text-align: left; font-size: 0.9rem; color: var(--dark);">
                                        <i class="fas fa-sun" style="width: 24px;"></i> Light
                                        <i class="fas fa-check ms-auto checkmark text-primary" style="display: none; margin-left: auto;"></i>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item flex items-center theme-switcher w-full" data-theme-value="dark" style="padding: 0.5rem 1rem; background: none; border: none; cursor: pointer; text-align: left; font-size: 0.9rem; color: var(--dark);">
                                        <i class="fas fa-moon" style="width: 24px;"></i> Dark
                                        <i class="fas fa-check ms-auto checkmark text-primary" style="display: none; margin-left: auto;"></i>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item flex items-center theme-switcher w-full active" data-theme-value="auto" style="padding: 0.5rem 1rem; background: none; border: none; cursor: pointer; text-align: left; font-size: 0.9rem; color: var(--dark);">
                                        <i class="fas fa-circle-half-stroke" style="width: 24px;"></i> Auto
                                        <i class="fas fa-check ms-auto checkmark text-primary" style="display: block; margin-left: auto;"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="flex items-center gap-3" style="cursor: pointer;">
                            <div style="width: 36px; height: 36px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                {{ strtoupper(substr(Auth::check() ? Auth::user()->nama_lengkap : session('name', 'User'), 0, 1)) }}
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span class="font-semibold text-dark" style="font-size: 0.875rem; line-height: 1;">{{ Auth::check() ? Auth::user()->nama_lengkap : session('name', 'Admin Name') }}</span>
                                <span class="text-gray" style="font-size: 0.75rem;">{{ Auth::check() ? ucfirst(Auth::user()->role) : ucfirst(session('role', 'admin')) }}</span>
                            </div>
                        </div>
                        <a href="/logout" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </header>

                <!-- Page Content -->
                <div class="page-content">
                    @yield('content')
                </div>
            </main>
        </div>
    @endif

    @stack('scripts')
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input && icon) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }

        // Logic Theme Switcher - Dijalankan hanya di Dashboard
        @if(trim($__env->yieldContent('layout', 'dashboard')) !== 'auth')
        document.addEventListener('DOMContentLoaded', () => {
            const themeBtn = document.getElementById('themeDropdownBtn');
            const themeMenu = document.getElementById('themeDropdownMenu');
            const themeSwitchers = document.querySelectorAll('.theme-switcher');
            const activeThemeIcon = document.getElementById('theme-icon-active');
            
            // Toggle Theme Dropdown
            if(themeBtn) {
                themeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    themeMenu.style.display = themeMenu.style.display === 'none' ? 'block' : 'none';
                    const notifMenu = document.getElementById('notifDropdownMenu');
                    if (notifMenu) notifMenu.style.display = 'none';
                });
                
                document.addEventListener('click', (e) => {
                    if (!themeBtn.contains(e.target) && !themeMenu.contains(e.target)) {
                        themeMenu.style.display = 'none';
                    }
                });
            }

            // Toggle Notification Dropdown
            const notifBtn = document.getElementById('notifDropdownBtn');
            const notifMenu = document.getElementById('notifDropdownMenu');
            if (notifBtn && notifMenu) {
                notifBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    notifMenu.style.display = notifMenu.style.display === 'none' ? 'block' : 'none';
                    if (themeMenu) themeMenu.style.display = 'none';
                });

                document.addEventListener('click', (e) => {
                    if (!notifBtn.contains(e.target) && !notifMenu.contains(e.target)) {
                        notifMenu.style.display = 'none';
                    }
                });
            }

            const themeIcons = { 'light': 'fa-sun', 'dark': 'fa-moon', 'auto': 'fa-circle-half-stroke' };
            const getStoredTheme = () => localStorage.getItem('theme') || 'auto';

            const applyTheme = (theme) => {
                let appliedTheme = theme;
                if (theme === 'auto') {
                    const isSystemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    appliedTheme = isSystemDark ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-theme', appliedTheme);
                } else {
                    document.documentElement.setAttribute('data-theme', theme);
                }
                console.log('Fungsi applyTheme dipanggil! Tema diubah ke:', appliedTheme);
            };

            const updateDropdownUI = (theme) => {
                if(activeThemeIcon) activeThemeIcon.className = `fas ${themeIcons[theme]}`;

                themeSwitchers.forEach(btn => {
                    const btnThemeValue = btn.getAttribute('data-theme-value');
                    const checkmark = btn.querySelector('.checkmark');
                    
                    if (btnThemeValue === theme) {
                        btn.style.color = 'var(--primary)';
                        btn.style.fontWeight = '600';
                        if(checkmark) checkmark.style.display = 'block';
                    } else {
                        btn.style.color = 'var(--text-main, var(--dark))';
                        btn.style.fontWeight = 'normal';
                        if(checkmark) checkmark.style.display = 'none';
                    }
                });
            };

            const currentTheme = getStoredTheme();
            updateDropdownUI(currentTheme);

            themeSwitchers.forEach(button => {
                button.addEventListener('click', () => {
                    const selectedTheme = button.getAttribute('data-theme-value');
                    localStorage.setItem('theme', selectedTheme);
                    applyTheme(selectedTheme);
                    updateDropdownUI(selectedTheme);
                    if(themeMenu) themeMenu.style.display = 'none';
                });
            });

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (getStoredTheme() === 'auto') {
                    applyTheme('auto');
                }
            });
        });
        @endif
    </script>
</body>
</html>
