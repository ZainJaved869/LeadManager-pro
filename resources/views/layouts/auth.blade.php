<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts (optional) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-gradient-premium {
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
        }
        .glass-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .input-premium {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            transition: all 0.3s;
        }
        .input-premium:focus {
            background: rgba(255,255,255,0.1);
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.3);
            outline: none;
        }
        .input-premium::placeholder {
            color: rgba(255,255,255,0.4);
        }
        .btn-gold {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transition: all 0.3s;
        }
        .btn-gold:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }
        .auth-link {
            color: #fbbf24;
            transition: color 0.2s;
        }
        .auth-link:hover {
            color: #f59e0b;
            text-decoration: underline;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-premium min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            <!-- Logo / Brand – exactly like the welcome screen -->
            <div class="text-center mb-6">
                <h1 class="text-4xl font-bold text-white tracking-tight">
                    LeadManager<span class="text-yellow-400">Pro</span>
                </h1>
                <p class="text-gray-300 text-sm mt-1">AI-Powered CRM &amp; Sales Management Platform</p>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-semibold text-white text-center mb-6">
                @yield('auth-title')
            </h2>

            <!-- Content -->
            @yield('auth-content')

            <!-- Footer – now shows LeadManager Pro -->
            <div class="mt-6 text-center text-gray-400 text-xs">
                &copy; {{ date('Y') }} LeadManager Pro. All rights reserved.
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>