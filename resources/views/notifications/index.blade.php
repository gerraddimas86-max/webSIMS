@extends('layouts.app')

@section('content')

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeUp {
        animation: fadeUp 0.5s ease both;
    }
    
    .notification-item {
        transition: all 0.2s ease;
    }
    
    .notification-item:hover {
        background: rgba(255,255,255,0.02);
    }
    
    .notification-item.unread {
        background: rgba(96,165,250,0.05);
        border-left: 3px solid #60a5fa;
    }

    .bg-like { background: rgba(59,130,246,0.1); }
    .bg-comment { background: rgba(34,197,94,0.1); }
    .bg-connection-request { background: rgba(234,179,8,0.1); }
    .bg-connection-accepted { background: rgba(16,185,129,0.1); }
    .bg-new-post { background: rgba(168,85,247,0.1); }
    .bg-default { background: rgba(255,255,255,0.05); }

</style>

<div class="w-full max-w-3xl mx-auto px-4 py-6 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
        <div>
            <h1 class="font-['DM_Serif_Display'] text-[1.65rem] text-[#f0f4f8] mb-1">Notifikasi</h1>
            <p class="text-[0.875rem] text-white/45">Pemberitahuan terbaru untukmu</p>
        </div>
        @if($notifications->count() > 0)
            <button onclick="markAllAsRead()" class="text-[0.75rem] text-white/45 hover:text-primary-400 transition-all">
                <i class="fa-regular fa-check-circle mr-1"></i>Tandai semua telah dibaca
            </button>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden animate-fadeUp opacity-0" style="animation-delay: 0.1s">
        @forelse($notifications as $notif)
            <a href="{{ $notif->action_url }}" 
               onclick="markAsRead({{ $notif->id }})"
               class="notification-item block px-5 py-4 border-b border-white/7 last:border-b-0 {{ !$notif->is_read ? 'unread' : '' }}">
                <div class="flex items-start gap-3">
                    {{-- Icon berdasarkan type --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                    @if($notif->type == 'like') bg-like
                    @elseif($notif->type == 'comment') bg-comment
                    @elseif($notif->type == 'connection_request') bg-connection-request
                    @elseif($notif->type == 'connection_accepted') bg-connection-accepted
                    @elseif($notif->type == 'new_post') bg-new-post
                    @else bg-default
                    @endif">
                        @if($notif->type == 'like')
                            <i class="fa-regular fa-thumbs-up text-primary-400"></i>
                        @elseif($notif->type == 'comment')
                            <i class="fa-regular fa-comment text-green-600"></i>
                        @elseif($notif->type == 'connection_request')
                            <i class="fa-solid fa-user-plus text-yellow-600"></i>
                        @elseif($notif->type == 'connection_accepted')
                            <i class="fa-regular fa-circle-check text-emerald-600"></i>
                        @elseif($notif->type == 'new_post')
                            <i class="fa-regular fa-newspaper text-purple-600"></i>
                        @else
                            <i class="fa-regular fa-bell text-white/30"></i>
                        @endif
                    </div>
                    
                    {{-- Content --}}
                    <div class="flex-1">
                        <p class="text-sm {{ !$notif->is_read ? 'text-[#f0f4f8] font-semibold' : 'text-white/45' }}">
                            {{ $notif->message }}
                        </p>
                        <p class="text-[0.7rem] text-white/22 mt-1">
                            {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>
                    
                    {{-- Unread dot --}}
                    @if(!$notif->is_read)
                        <div class="w-2 h-2 rounded-full bg-primary-400 shrink-0 mt-2"></div>
                    @endif
                </div>
            </a>
        @empty
            <div class="text-center py-12">
                <div class="flex flex-col items-center gap-3">
                    <i class="fa-regular fa-bell-slash text-4xl text-white/22"></i>
                    <p class="text-white/45 text-sm">Belum ada notifikasi</p>
                    <span class="text-white/22 text-xs">Aktifitas seperti like, komentar, dan koneksi akan muncul di sini</span>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="mt-5">
            {{ $notifications->links('pagination::tailwind') }}
        </div>
    @endif

</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    
    async function markAsRead(id) {
        try {
            await fetch(`/notifications/${id}/read`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    async function markAllAsRead() {
        try {
            const response = await fetch('/notifications/read-all', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>

@endsection