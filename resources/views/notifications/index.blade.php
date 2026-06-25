@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Notifications</h1>
            <p class="text-slate-500 text-sm">All your notifications in one place.</p>
        </div>
        <div class="flex gap-2">
            @if(Auth::user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                        <i class="fas fa-check-double mr-2"></i> Mark All as Read
                    </button>
                </form>
            @endif
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        @if($notifications->count() > 0)
            <ul class="divide-y divide-slate-100">
                @foreach($notifications as $notification)
                    <li class="hover:bg-slate-50 transition {{ $notification->read_at ? '' : 'bg-yellow-50/50' }}">
                        <a href="{{ route('notifications.mark-read', $notification->id) }}" class="block px-6 py-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-medium text-slate-800">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                    <p class="text-sm text-slate-600 mt-1">{{ $notification->data['description'] ?? '' }}</p>
                                    <p class="text-xs text-slate-400 mt-2">{{ $notification->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                @if(!$notification->read_at)
                                    <span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0 mt-2"></span>
                                @endif
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="p-12 text-center text-slate-400">
                <i class="fas fa-bell-slash text-4xl block mb-3"></i>
                No notifications yet.
            </div>
        @endif
    </div>
    <div class="mt-4">{{ $notifications->links() }}</div>
</div>
@endsection