@forelse(auth()->user()->unreadNotifications as $notification)
    <a href="#" class="dropdown-item">
        <i class="fas fa-envelope mr-2"></i>
        <span class="text-muted text-sm">{{ $notification->data['title'] }}</span>
        <p class="text-sm">{{ $notification->data['message'] }}</p>
        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
    </a>
    <div class="dropdown-divider"></div>
@empty
    <span class="dropdown-item dropdown-header">No new notifications</span>
@endforelse