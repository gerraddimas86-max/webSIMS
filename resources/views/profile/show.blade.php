@extends('layouts.app')

@section('content')

<div class="w-full max-w-[680px] mx-auto px-4 pb-16 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- Back Button --}}
    <div class="mb-5 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-white/45 hover:text-[#f0f4f8] transition-colors">
            <i class="fa-solid fa-arrow-left text-sm"></i>
            <span class="text-sm">Kembali</span>
        </a>
    </div>

    {{-- Profile Header --}}
    <div class="relative rounded-2xl overflow-hidden mb-1 shadow-[0_4px_24px_rgba(0,0,0,0.35)] animate-fadeUp opacity-0" style="animation-delay: 0.05s">
        <img src="{{ asset('images/unsri_bg.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover brightness-[0.35] saturate-[0.6]">
        <div class="absolute inset-0 bg-gradient-to-br from-[#080c12]/85 to-[#161b24]/70"></div>
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.14)_0%,transparent_70%)] pointer-events-none"></div>
        
        <div class="relative z-10 p-6 flex justify-between items-center gap-4">
            <div>
                <div class="font-['DM_Serif_Display'] text-[1.4rem] text-[#f0f4f8] mb-0.5">{{ $user->name }}</div>
                <div class="text-[0.8rem] text-white/45 mb-3">{{ $user->email }}</div>
                
                {{-- Connection Status Badge --}}
                @if($connectionStatus === 'accepted')
                    <span class="inline-flex items-center gap-1.5 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-full px-3 py-0.5 text-[0.72rem] font-semibold text-[#4ade80] tracking-wide">
                        <i class="fa-regular fa-circle-check text-[10px]"></i>
                        Terhubung
                    </span>
                @elseif($connectionStatus === 'pending')
                    <span class="inline-flex items-center gap-1.5 bg-[#fbbf24]/10 border border-[#fbbf24]/20 rounded-full px-3 py-0.5 text-[0.72rem] font-semibold text-[#fbbf24] tracking-wide">
                        <i class="fa-regular fa-clock text-[10px]"></i>
                        Menunggu Konfirmasi
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-[#242d3d] border border-white/7 rounded-full px-3 py-0.5 text-[0.72rem] font-semibold text-white/45 tracking-wide">
                        <i class="fa-regular fa-user text-[10px]"></i>
                        Belum Terhubung
                    </span>
                @endif
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-[#242d3d] to-[#2e3a4e] border border-white/13 flex items-center justify-center text-[1.5rem] font-bold text-[#60a5fa]">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 bg-[#161b24] border border-white/7 rounded-2xl mb-3 overflow-hidden animate-fadeUp opacity-0" style="animation-delay: 0.1s">
        <div class="text-center py-5 px-2 border-r border-white/7">
            <div class="font-['DM_Serif_Display'] text-[1.65rem] text-[#60a5fa] leading-none">{{ $user->connections_count ?? 0 }}</div>
            <div class="text-[0.68rem] font-bold tracking-[0.1em] uppercase text-white/22 mt-1">Connection</div>
        </div>
        <div class="text-center py-5 px-2">
            <div class="font-['DM_Serif_Display'] text-[1.65rem] text-[#60a5fa] leading-none">{{ $user->events_count ?? 0 }}</div>
            <div class="text-[0.68rem] font-bold tracking-[0.1em] uppercase text-white/22 mt-1">Event</div>
        </div>
    </div>

    {{-- About --}}
    <div class="flex items-center gap-3 my-4">
        <div class="flex-1 h-px bg-white/7"></div>
        <span class="text-[0.66rem] font-bold tracking-[0.12em] uppercase text-white/22 whitespace-nowrap">
            <i class="fa-regular fa-user mr-1.5 text-[9px]"></i>Tentang
        </span>
        <div class="flex-1 h-px bg-white/7"></div>
    </div>

    <div class="bg-[#161b24] border border-white/7 rounded-2xl hover:border-white/13 hover:shadow-[0_8px_28px_rgba(0,0,0,0.3)] transition-all animate-fadeUp opacity-0" style="animation-delay: 0.15s">
        <div class="p-5">
            <div class="flex items-center gap-2 mb-3">
                <i class="fa-regular fa-calendar-alt text-white/30 text-[12px]"></i>
                <span class="text-white/30 text-xs">Angkatan {{ $user->angkatan ?? '?' }}</span>
                <i class="fa-solid fa-graduation-cap text-white/30 text-[12px] ml-2"></i>
                <span class="text-white/30 text-xs">{{ $user->prodi ?? 'Program Studi' }}</span>
            </div>
            <p class="text-[0.875rem] leading-relaxed text-white/45">{{ $user->bio ?? 'Belum ada deskripsi.' }}</p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-3 mt-4 animate-fadeUp opacity-0" style="animation-delay: 0.2s">
        @if($connectionStatus === 'accepted')
            <a href="{{ route('connections.chat', $user->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-[#2563eb] text-white rounded-xl px-5 py-2.5 font-semibold text-sm hover:bg-[#3b82f6] hover:-translate-y-px transition-all">
                <i class="fa-regular fa-comment text-[12px]"></i>
                Kirim Pesan
            </a>
        @elseif($connectionStatus === 'pending')
            <button class="flex-1 inline-flex items-center justify-center gap-2 bg-[#fbbf24]/10 border border-[#fbbf24]/20 text-[#fbbf24] rounded-xl px-5 py-2.5 font-semibold text-sm cursor-not-allowed" disabled>
                <i class="fa-regular fa-clock text-[12px]"></i>
                Menunggu Konfirmasi
            </button>
        @else
            <button onclick="sendRequest({{ $user->id }}, this)" class="flex-1 inline-flex items-center justify-center gap-2 bg-[#2563eb] text-white rounded-xl px-5 py-2.5 font-semibold text-sm hover:bg-[#3b82f6] hover:-translate-y-px transition-all cursor-pointer">
                <i class="fa-solid fa-user-plus text-[12px]"></i>
                Hubungkan
            </button>
        @endif
        
        <a href="{{ url()->previous() }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-transparent border border-white/13 text-white/45 rounded-xl px-5 py-2.5 font-semibold text-sm hover:border-white/20 hover:text-[#f0f4f8] transition-all">
            <i class="fa-solid fa-xmark text-[12px]"></i>
            Kembali
        </a>
    </div>

    {{-- Connections List (Mutual Friends) --}}
    @if($connections->count() > 0)
    <div class="flex items-center gap-3 my-4 mt-6">
        <div class="flex-1 h-px bg-white/7"></div>
        <span class="text-[0.66rem] font-bold tracking-[0.12em] uppercase text-white/22 whitespace-nowrap">
            <i class="fa-solid fa-users mr-1.5 text-[9px]"></i>{{ $user->name }}'s Connections
        </span>
        <div class="flex-1 h-px bg-white/7"></div>
    </div>

    <div class="bg-[#161b24] border border-white/7 rounded-2xl hover:border-white/13 hover:shadow-[0_8px_28px_rgba(0,0,0,0.3)] transition-all animate-fadeUp opacity-0" style="animation-delay: 0.25s">
        <div class="p-5">
            <div class="grid grid-cols-6 gap-3">
                @foreach($connections as $conn)
                    <a href="{{ route('profile.show', $conn->id) }}" class="flex flex-col items-center gap-1 cursor-pointer group">
                        <div class="w-11.5 h-11.5 rounded-[0.625rem] bg-gradient-to-br from-[#242d3d] to-[#2e3a4e] border border-white/13 flex items-center justify-center text-[0.8rem] font-bold text-[#60a5fa] transition-all group-hover:border-blue-500/45 group-hover:-translate-y-0.5">
                            {{ strtoupper(substr($conn->name, 0, 2)) }}
                        </div>
                        <span class="text-[0.65rem] font-medium text-white/30 text-center truncate max-w-[56px]">{{ $conn->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeUp {
        animation: fadeUp 0.5s ease both;
    }
</style>

<script>
    function csrf() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }
    
    async function sendRequest(userId, btn) {
        btn.disabled = true;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<div class="spinner"></div>';
        
        try {
            const response = await fetch('/connections/request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ user_id: userId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Request koneksi berhasil dikirim!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Gagal mengirim request', 'error');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch (error) {
            console.error(error);
            showToast('Terjadi kesalahan', 'error');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }
    
    function showToast(msg, type) {
        const toast = document.getElementById('toast') || createToast();
        const icon = type === 'success' ? 'fa-regular fa-circle-check' : 'fa-regular fa-circle-xmark';
        const color = type === 'success' ? '#4ade80' : '#f87171';
        const bg = type === 'success' ? 'rgba(74,222,128,.15)' : 'rgba(248,113,113,.12)';
        
        toast.style.background = bg;
        toast.style.border = '1px solid ' + (type === 'success' ? 'rgba(74,222,128,.25)' : 'rgba(248,113,113,.22)');
        toast.style.color = color;
        toast.innerHTML = '<i class="' + icon + ' text-[13px]"></i><span>' + msg + '</span>';
        toast.classList.add('show');
        
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
    
    function createToast() {
        const toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'fixed bottom-7 left-1/2 -translate-x-1/2 translate-y-3 flex items-center gap-2 py-2 px-5 rounded-full font-["DM_Sans"] text-[0.82rem] font-medium shadow-[0_8px_24px_rgba(0,0,0,0.4)] opacity-0 pointer-events-none transition-all duration-250 z-[9999] whitespace-nowrap';
        document.body.appendChild(toast);
        return toast;
    }
</script>

<style>
    .spinner {
        width: 13px; height: 13px;
        border: 2px solid rgba(96,165,250,.3);
        border-top-color: #60a5fa;
        border-radius: 50%;
        animation: spin .6s linear infinite;
        flex-shrink: 0;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

@endsection