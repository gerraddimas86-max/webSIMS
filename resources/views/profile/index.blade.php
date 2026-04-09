@extends('layouts.app')

@section('content')

<div class="w-full max-w-[680px] mx-auto px-4 pb-16 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- HERO --}}
    <div class="relative h-[180px] overflow-hidden rounded-b-2xl animate-fadeUp opacity-0" style="animation: fadeUp 0.5s ease both 0s">
        <img class="absolute inset-0 w-full h-full object-cover brightness-[0.35] saturate-[0.6]" src="{{ asset('images/unsri_bg.jpg') }}" alt="Unsri">
        <div class="absolute inset-0 bg-gradient-to-br from-[#080c12]/88 to-[#161b24]/75"></div>
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.13)_0%,transparent_70%)] pointer-events-none"></div>
        <div class="absolute bottom-4 left-6 text-[0.68rem] font-bold tracking-[0.15em] uppercase text-[#fbbf24] opacity-85">
            <i class="fa-solid fa-sun mr-1.5 text-[9px]"></i>
            Solidarity &amp; Innovation Movement of Sriwijaya
        </div>
    </div>

    {{-- AVATAR STRIP --}}
    <div class="bg-[#161b24] border border-white/7 border-t-0 rounded-b-2xl px-6 pb-5 flex flex-wrap items-end gap-5 animate-fadeUp opacity-0" style="animation: fadeUp 0.5s ease both 0.06s">
        <div class="w-24 h-24 rounded-xl bg-gradient-to-br from-[#242d3d] to-[#2e3a4e] border border-white/13 flex items-center justify-center font-['DM_Serif_Display'] text-[2rem] font-bold text-[#60a5fa] shadow-[0_4px_20px_rgba(0,0,0,0.45)]">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>

        <div class="flex-1 pb-1">
    <div class="font-['DM_Serif_Display'] text-[1.4rem] text-[#f0f4f8] leading-tight mb-0.5">{{ $user->name }}</div>
    
    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[0.78rem] text-white/30 mb-1.5">
        <span class="inline-flex items-center gap-1">
            <i class="fa-regular fa-calendar-alt text-[10px]"></i>
            Angkatan {{ $user->angkatan ?? '?' }}
        </span>
        <span class="inline-flex items-center gap-1">
            <i class="fa-solid fa-graduation-cap text-[10px]"></i>
            {{ $user->prodi ?? 'Program Studi' }}
        </span>
        <span class="text-white/20">•</span>
        <span class="inline-flex items-center gap-1">
            <i class="fa-solid fa-university text-[10px]"></i>
            Unsri
        </span>
    </div>
    
    <span class="inline-flex items-center gap-1.5 bg-[#fbbf24]/10 border border-[#fbbf24]/20 rounded-full px-2.5 py-0.5 text-[0.7rem] font-bold text-[#fbbf24] tracking-wide">
        <i class="fa-solid fa-id-card text-[9px]"></i>
        Mahasiswa Aktif
    </span>
    </div>

        <div class="pb-1 ml-auto">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-1.5 bg-[#2563eb] text-white no-underline text-[0.82rem] font-semibold px-5 py-2 rounded-xl border border-blue-500/40 hover:bg-[#3b82f6] hover:-translate-y-px hover:shadow-[0_6px_16px_rgba(59,130,246,0.3)] transition-all">
                <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                Edit Profil
            </a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 bg-[#161b24] border border-white/7 rounded-2xl mb-3 overflow-hidden animate-fadeUp opacity-0" style="animation: fadeUp 0.5s ease both 0.12s">
        <div class="text-center py-5 px-2 border-r border-white/7">
            <div class="font-['DM_Serif_Display'] text-[1.65rem] text-[#60a5fa] leading-none">{{ $user->connections_count ?? 0 }}</div>
            <div class="text-[0.68rem] font-bold tracking-[0.1em] uppercase text-white/22 mt-1">Connection</div>
        </div>
        <div class="text-center py-5 px-2">
            <div class="font-['DM_Serif_Display'] text-[1.65rem] text-[#60a5fa] leading-none">{{ $user->events_count ?? 0 }}</div>
            <div class="text-[0.68rem] font-bold tracking-[0.1em] uppercase text-white/22 mt-1">Event</div>
        </div>
    </div>

    {{-- ABOUT --}}
    <div class="flex items-center gap-3 my-4">
        <div class="flex-1 h-px bg-white/7"></div>
        <span class="text-[0.66rem] font-bold tracking-[0.12em] uppercase text-white/22 whitespace-nowrap">
            <i class="fa-regular fa-user mr-1.5 text-[9px]"></i>Tentang
        </span>
        <div class="flex-1 h-px bg-white/7"></div>
    </div>

    <div class="bg-[#161b24] border border-white/7 rounded-2xl hover:border-white/13 hover:shadow-[0_8px_28px_rgba(0,0,0,0.3)] transition-all animate-fadeUp opacity-0" style="animation: fadeUp 0.5s ease both 0.18s">
        <div class="p-5">
            <p class="text-[0.875rem] leading-relaxed text-white/45">{{ $user->bio ?? 'Belum ada deskripsi. Klik Edit Profil untuk menambahkan bio.' }}</p>
        </div>
    </div>

    {{-- CONNECTIONS --}}
    <div class="flex items-center gap-3 my-4">
        <div class="flex-1 h-px bg-white/7"></div>
        <span class="text-[0.66rem] font-bold tracking-[0.12em] uppercase text-white/22 whitespace-nowrap">
            <i class="fa-solid fa-users mr-1.5 text-[9px]"></i>Connection
        </span>
        <div class="flex-1 h-px bg-white/7"></div>
    </div>

    <div class="bg-[#161b24] border border-white/7 rounded-2xl hover:border-white/13 hover:shadow-[0_8px_28px_rgba(0,0,0,0.3)] transition-all animate-fadeUp opacity-0" style="animation: fadeUp 0.5s ease both 0.18s">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-white/7">
            <span class="flex items-center gap-1.5 text-[0.68rem] font-bold tracking-[0.1em] uppercase text-white/22">
                <span class="w-1.5 h-1.5 rounded-full bg-[#60a5fa]"></span>
                Teman kamu
            </span>
            <a href="{{ route('connections.index') }}" class="text-[0.74rem] font-semibold text-[#60a5fa] no-underline hover:opacity-75 transition-all">Lihat semua →</a>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-6 gap-3">
                @forelse($connections ?? [] as $conn)
                    <div class="flex flex-col items-center gap-1 cursor-pointer group">
                        <div class="w-11.5 h-11.5 rounded-[0.625rem] bg-gradient-to-br from-[#242d3d] to-[#2e3a4e] border border-white/13 flex items-center justify-center text-[0.8rem] font-bold text-[#60a5fa] overflow-hidden transition-all group-hover:border-blue-500/45 group-hover:-translate-y-0.5">
                            {{ strtoupper(substr($conn->name, 0, 2)) }}
                        </div>
                        <span class="text-[0.65rem] font-medium text-white/30 text-center truncate max-w-[56px]">{{ $conn->name }}</span>
                    </div>
                @empty
                    <div class="col-span-full text-center py-4 text-white/30 text-sm">
                        Belum ada teman. Hubungkan dengan user lain!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

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

@endsection