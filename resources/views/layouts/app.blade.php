<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LeadManager Pro - @yield('title', 'Dashboard')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome 6 (Free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; }
        .sidebar {
            background: #0f172a;
            min-height: 100vh;
            width: 260px;
            transition: transform 0.3s ease;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            color: #94a3b8;
            transition: all 0.2s;
            font-weight: 500;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.05);
            color: #f1f5f9;
        }
        .sidebar-link.active {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }
        .sidebar-link i {
            width: 1.25rem;
            text-align: center;
        }
        .mobile-toggle {
            display: none;
        }

        /* Brand mark – same as admin */
        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 8px;
            color: #0f172a;
            font-weight: 800;
            font-size: 1.2rem;
            font-family: 'Inter', sans-serif;
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(245, 158, 11, 0.3);
        }
        .brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.3px;
        }
        .brand-text .pro {
            font-size: 0.7rem;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #0f172a;
            padding: 0.1rem 0.5rem;
            border-radius: 9999px;
            margin-left: 0.2rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        /* Dropdown */
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            min-width: 200px;
            padding: 0.5rem;
            z-index: 50;
            border: 1px solid #e2e8f0;
        }
        .dropdown-menu.active {
            display: block;
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
            color: #1e293b;
            transition: background 0.15s;
            font-size: 0.9rem;
        }
        .dropdown-item:hover {
            background: #f1f5f9;
        }
        .dropdown-item.text-red-500:hover {
            background: #fef2f2;
        }
        /* Notification dropdown */
        .notification-dropdown {
            right: 0;
            left: auto;
            width: 380px;
            max-height: 400px;
            overflow-y: auto;
        }
        .notification-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .notification-item:last-child {
            border-bottom: none;
        }
        .notification-item:hover {
            background: #f8fafc;
        }
        .notification-item.unread {
            background: #fefce8;
        }
        @media (max-width: 640px) {
            .notification-dropdown {
                width: 300px;
            }
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                transform: translateX(-100%);
                z-index: 50;
                width: 280px;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .mobile-toggle {
                display: block;
            }
            .overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 40;
            }
            .overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="overlay" onclick="toggleSidebar()"></div>

    <div class="flex min-h-screen">
        <!-- Sidebar (no user info) -->
        <aside id="sidebar" class="sidebar flex-shrink-0">
            <div class="flex flex-col h-full">
                <!-- Brand: "L" Mark + LeadManager Pro (exact match to admin) -->
                <div class="flex items-center justify-between px-6 py-6 border-b border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="brand-mark">L</span>
                        <span class="brand-text">LeadManager<span class="pro">PRO</span></span>
                    </div>
                    <button onclick="toggleSidebar()" class="text-white/60 hover:text-white lg:hidden">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                    <a href="{{ route('leads.index') }}" class="sidebar-link {{ request()->routeIs('leads.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Leads
                    </a>
                    <a href="{{ route('pipeline.index') }}" class="sidebar-link {{ request()->routeIs('pipeline.*') ? 'active' : '' }}">
                        <i class="fas fa-columns"></i> Pipeline
                    </a>
                    <a href="{{ route('companies.index') }}" class="sidebar-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i> Customers
                    </a>
                    <a href="{{ route('tasks.index') }}" class="sidebar-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i> Tasks
                    </a>
                    <a href="{{ route('analytics.index') }}" class="sidebar-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Analytics
                    </a>
                    <a href="{{ route('ai.index') }}" class="sidebar-link {{ request()->routeIs('ai.*') ? 'active' : '' }}">
                        <i class="fas fa-brain"></i> AI Assistant
                    </a>
                    <a href="{{ route('calendar.index') }}" class="sidebar-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Calendar
                    </a>
                    <a href="{{ route('subscription.plans') }}" class="sidebar-link {{ request()->routeIs('subscription.*') ? 'active' : '' }}">
                        <i class="fas fa-crown"></i> Subscription
                    </a>
                </nav>

                <!-- Sidebar footer -->
                <div class="px-4 py-4 border-t border-white/5"></div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-h-screen">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="mobile-toggle text-slate-700 hover:text-slate-900">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <span class="text-sm font-bold text-slate-800 lg:hidden">LeadManager Pro</span>
                </div>

                <!-- Right: Notification Bell + User dropdown -->
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button onclick="toggleNotifications()" class="text-slate-500 hover:text-slate-700 focus:outline-none relative">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center {{ Auth::user()->unreadNotifications->count() > 0 ? '' : 'hidden' }}">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        </button>
                        <div id="notificationDropdown" class="dropdown-menu notification-dropdown hidden">
                            <div class="p-4 text-center text-slate-500" id="notification-loading">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Loading...
                            </div>
                        </div>
                    </div>

                    <!-- User dropdown -->
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center gap-2 text-sm text-slate-700 hover:text-slate-900 focus:outline-none">
                            <span class="hidden sm:inline font-medium">{{ Auth::user()->name }}</span>
                            <div class="w-9 h-9 rounded-full bg-yellow-400/20 flex items-center justify-center text-yellow-600 font-bold text-sm border-2 border-yellow-200/50">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>

                        <!-- User Dropdown -->
                        <div id="userDropdown" class="dropdown-menu">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user-circle w-5 text-slate-400"></i> Profile
                            </a>
                            <a href="{{ route('settings.index') }}" class="dropdown-item">
                                <i class="fas fa-cog w-5 text-slate-400"></i> Settings
                            </a>
                            <a href="{{ route('notifications.index') }}" class="dropdown-item">
                                <i class="fas fa-bell w-5 text-slate-400"></i> Notifications
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5">{{ Auth::user()->unreadNotifications->count() }}</span>
                                @endif
                            </a>
                            <hr class="my-1 border-slate-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item w-full text-red-500 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt w-5 text-red-400"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 p-4 md:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // ========== SIDEBAR ==========
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('sidebar').classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        });

        // ========== USER DROPDOWN ==========
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('button');
            if (!button || !button.onclick || !button.onclick.toString().includes('toggleDropdown')) {
                if (dropdown && !dropdown.contains(event.target)) {
                    dropdown.classList.remove('active');
                }
            }
        });

        // ========== NOTIFICATIONS ==========
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                loadNotifications();
            }
        }

        function loadNotifications() {
            const container = document.getElementById('notificationDropdown');
            const loading = document.getElementById('notification-loading');
            if (!loading) return;

            fetch('{{ route("notifications.latest") }}')
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                    updateBadge();
                })
                .catch(() => {
                    container.innerHTML = '<div class="p-4 text-center text-slate-400">Failed to load notifications.</div>';
                });
        }

        function updateBadge() {
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (data.count > 0) {
                        badge.classList.remove('hidden');
                        badge.textContent = data.count;
                    } else {
                        badge.classList.add('hidden');
                    }
                });
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const button = event.target.closest('button');
            if (!button || !button.onclick || !button.onclick.toString().includes('toggleNotifications')) {
                if (dropdown && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const dropdown = document.getElementById('notificationDropdown');
                if (dropdown) {
                    dropdown.classList.add('hidden');
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>