<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div class="w-full max-w-md mx-4">
        <div class="bg-white rounded-xl shadow-2xl p-8">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-slate-800">Admin <span class="text-yellow-500">Login</span></h1>
                <p class="text-slate-500 text-sm">Access the admin dashboard</p>
            </div>
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                    {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Password</label>
                        <input type="password" name="password" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </div>
            </form>
            <div class="mt-4 text-center text-sm text-slate-500">
                <a href="{{ route('login') }}" class="text-yellow-600 hover:underline">Back to main site</a>
            </div>
        </div>
    </div>
</body>
</html>