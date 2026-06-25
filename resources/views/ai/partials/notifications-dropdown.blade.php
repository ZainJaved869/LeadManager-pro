<div class="dropdown-menu notification-dropdown" style="width: 380px; max-height: 400px; overflow-y: auto;">
    @if($notifications->count() > 0)
        @foreach($notifications as $notification)
            <a href="{{ route('notifications.mark-read', $notification->id) }}" class="dropdown-item notification-item {{ $notification->read_at ? '' : 'bg-yellow-50' }}">
                <div>
                    <p class="text-sm font-medium text-slate-800">{{ $notification->data['title'] ?? 'Notification' }}</p>
                    <p class="text-xs text-slate-500">{{ $notification->data['description'] ?? '' }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notification->read_at)
                    <span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0"></span>
                @endif
            </a>
        @endforeach
        <div class="border-t border-slate-100 pt-2 mt-2">
            <a href="{{ route('notifications.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 block text-center">View all notifications</a>
        </div>
    @else
        <div class="p-4 text-center text-slate-400">
            <i class="fas fa-bell-slash text-2xl block mb-2"></i>
            No new notifications
        </div>
    @endif
</div>