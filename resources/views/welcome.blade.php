<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LeadManager Pro – AI-Powered CRM</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            background: #0f172a;
            background-image: radial-gradient(ellipse at 10% 20%, #1e293b 0%, #0f172a 70%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow-x: hidden;
        }

        /* Animated background orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 12s ease-in-out infinite alternate;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: rgba(245, 158, 11, 0.15);
            top: -100px;
            left: -100px;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: rgba(99, 102, 241, 0.12);
            bottom: -150px;
            right: -150px;
            animation-delay: 4s;
        }

        .orb-3 {
            width: 300px;
            height: 300px;
            background: rgba(236, 72, 153, 0.10);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 8s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.1); }
            66% { transform: translate(-20px, 30px) scale(0.9); }
            100% { transform: translate(20px, -10px) scale(1.05); }
        }

        /* Glass card */
        .glass-premium {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6);
        }

        .glass-premium:hover {
            border-color: rgba(245, 158, 11, 0.2);
            transition: border-color 0.4s ease;
        }

        /* Gold gradient text */
        .text-gold {
            background: linear-gradient(135deg, #f59e0b, #d97706, #f59e0b);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 4s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Premium button */
        .btn-primary-premium {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #0f172a;
            font-weight: 700;
            padding: 0.875rem 2.5rem;
            border-radius: 9999px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 25px rgba(245, 158, 11, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-premium:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 40px rgba(245, 158, 11, 0.5);
        }

        .btn-outline-premium {
            background: transparent;
            color: #e2e8f0;
            font-weight: 600;
            padding: 0.875rem 2.5rem;
            border-radius: 9999px;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline-premium:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(245, 158, 11, 0.4);
            transform: scale(1.02);
        }

        /* Feature card */
        .feature-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 1rem;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .feature-item:hover {
            background: rgba(245, 158, 11, 0.06);
            border-color: rgba(245, 158, 11, 0.15);
            transform: translateY(-2px);
        }

        .feature-icon {
            color: #f59e0b;
            flex-shrink: 0;
        }

        /* Floating badge */
        .badge-premium {
            background: rgba(245, 158, 11, 0.12);
            color: #fbbf24;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            border: 1px solid rgba(245, 158, 11, 0.15);
            display: inline-block;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Responsive tweaks */
        @media (max-width: 640px) {
            .btn-primary-premium, .btn-outline-premium {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
                width: 100%;
                justify-content: center;
            }
            .feature-item {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

    <!-- Animated Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Main Content -->
    <div class="relative z-10 w-full max-w-5xl mx-auto">

        <!-- Glass Card -->
        <div class="glass-premium rounded-3xl p-8 md:p-12 lg:p-16">

           

            <!-- Logo / Brand -->
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">
                    <span class="text-white">LeadManager</span> <span class="text-gold">Pro</span>
                </h1>
                <p class="text-slate-300 text-sm md:text-base mt-2 font-light tracking-wider">
                    AI-Powered CRM &amp; Sales Management Platform
                </p>
            </div>

            <!-- Hero Heading -->
            <div class="text-center mb-6">
                <h2 class="text-2xl md:text-5xl font-bold leading-tight">
                    <span class="text-white">Transform Your</span><br>
                    <span class="text-gold">Sales Pipeline</span>
                    <span class="text-white">Today</span>
                </h2>
                <p class="text-slate-300 text-base md:text-lg max-w-2xl mx-auto mt-4 leading-relaxed">
                    AI-powered lead management, automated follow-ups, and real-time analytics
                    — all in one beautiful, multi-tenant platform.
                </p>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-4 mt-8">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary-premium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary-premium">
                            Get Started Free
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-outline-premium">
                                Create Account
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Trust Badges -->
            <div class="mt-8 flex flex-wrap justify-center gap-4 text-xs text-slate-400">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    No credit card required
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    14-day free trial
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Cancel anytime
                </span>
            </div>

            <!-- Feature Grid -->
            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="feature-item">
                    <svg class="feature-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="text-slate-300 text-sm font-medium">Secure &amp; Encrypted</span>
                </div>
                <div class="feature-item">
                    <svg class="feature-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-slate-300 text-sm font-medium">AI-Powered Insights</span>
                </div>
                <div class="feature-item">
                    <svg class="feature-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-slate-300 text-sm font-medium">Team Collaboration</span>
                </div>
                <div class="feature-item">
                    <svg class="feature-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="text-slate-300 text-sm font-medium">Real-Time Analytics</span>
                </div>
            </div>

           
        <!-- Footer -->
        <div class="mt-6 text-center text-slate-500 text-xs">
            &copy; {{ date('Y') }} LeadManager Pro. All rights reserved.
            
        </div>
    </div>

</body>
</html>