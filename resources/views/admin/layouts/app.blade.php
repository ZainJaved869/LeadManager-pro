<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LeadManager Pro - @yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #0f172a; }

        .admin-sidebar {
            background: #0f172a;
            min-height: 100vh;
            width: 280px;
            border-right: 1px solid #1e293b;
            transition: transform 0.3s ease;
        }

        .admin-sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            color: #94a3b8;
            transition: all 0.2s;
            font-weight: 500;
        }

        .admin-sidebar-link:hover {
            background: rgba(255,255,255,0.05);
            color: #f1f5f9;
        }

        .admin-sidebar-link.active {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }

        .admin-sidebar-link i {
            width: 1.25rem;
            text-align: center;
        }

        .admin-content {
            background: #f1f5f9;
            min-height: 100vh;
        }

        /* Brand mark – "L" in gold gradient */
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

        /* Avatar initials in header */
        .avatar-initials {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            background: rgba(245, 158, 11, 0.25);
            color: #fbbf24;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
        }

        .avatar-initials:hover {
            background: rgba(245, 158, 11, 0.4);
        }

        /* Dropdown menu for avatar */
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            background: #1e293b;
            border-radius: 0.75rem;
            min-width: 160px;
            padding: 0.5rem;
            z-index: 50;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            border: 1px solid #334155;
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
            color: #e2e8f0;
            transition: background 0.15s;
            font-size: 0.9rem;
            cursor: pointer;
            background: transparent;
            border: none;
            width: 100%;
            text-decoration: none;
        }

        .dropdown-item:hover {
            background: #334155;
        }

        .dropdown-item.text-red-400 {
            color: #f87171;
        }

        .dropdown-item.text-red-400:hover {
            background: #7f1d1d;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                transform: translateX(-100%);
                z-index: 50;
                width: 280px;
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 40;
            }

            .admin-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div id="adminOverlay" class="admin-overlay" onclick="toggleSidebar()"></div>

    <div class="flex">
        <!-- Sidebar -->
        <aside id="adminSidebar" class="admin-sidebar flex-shrink-0">
            <div class="flex flex-col h-full">
                <!-- Brand -->
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
                    <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}" class="admin-sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a href="{{ route('admin.tenants') }}" class="admin-sidebar-link {{ request()->routeIs('admin.tenants*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i> Tenants
                    </a>
                    <a href="{{ route('admin.subscriptions') }}" class="admin-sidebar-link {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}">
                        <i class="fas fa-crown"></i> Subscriptions
                    </a>
                    <a href="{{ route('admin.revenue') }}" class="admin-sidebar-link {{ request()->routeIs('admin.revenue*') ? 'active' : '' }}">
                        <i class="fas fa-dollar-sign"></i> Revenue
                    </a>
                    <hr class="border-white/5 my-4">
                    <a href="{{ route('admin.settings') }}" class="admin-sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i> System Settings
                    </a>
                    <a href="{{ route('admin.ai-settings') }}" class="admin-sidebar-link {{ request()->routeIs('admin.ai-settings*') ? 'active' : '' }}">
                        <i class="fas fa-brain"></i> AI Settings
                    </a>
                </nav>

                <!-- Sidebar footer – removed user info and logout -->
                <div class="px-4 py-4 border-t border-white/5"></div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-content flex-1">
            <!-- Header with avatar on the RIGHT -->
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between sticky top-0 z-30">
                <!-- Left side: mobile toggle + brand (visible on mobile) -->
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="text-slate-700 hover:text-slate-900 lg:hidden">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <span class="text-sm font-bold text-slate-800 lg:hidden">LeadManager</span>
                </div>

                <!-- Right side: Avatar Dropdown (only Logout) -->
                <div class="relative ml-auto"> <!-- ml-auto pushes it to the right -->
                    <button onclick="toggleDropdown()" class="flex items-center gap-2 text-sm text-slate-700 hover:text-slate-900 focus:outline-none">
                        <span class="hidden sm:inline font-medium">{{ Auth::user()->name }}</span>
                        <div class="avatar-initials">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </button>

                    <div id="userDropdown" class="dropdown-menu">
                        <div class="px-3 py-2 border-b border-white/10">
                            <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="dropdown-item text-red-400 hover:bg-red-900/30">
                                <i class="fas fa-sign-out-alt w-5 text-red-400"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-4 md:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('adminOverlay').classList.toggle('active');
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('adminSidebar').classList.remove('open');
                document.getElementById('adminOverlay').classList.remove('active');
            }
        });

        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('.relative');
            if (!button || !button.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>