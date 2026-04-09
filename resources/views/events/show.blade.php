@extends('layouts.app')

@section('content')

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeUp {
        animation: fadeUp 0.5s ease forwards;
    }
</style>

<div class="w-full max-w-4xl mx-auto px-4 py-8 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- Back Button --}}
    <div class="mb-6 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-white/45 hover:text-[#f0f4f8] transition-colors">
            <i class="fa-solid fa-circle-arrow-left text-sm"></i>
            <span class="text-sm">Kembali ke Daftar Event</span>
        </a>
    </div>

    {{-- Event Header --}}
    <div class="bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden animate-fadeUp opacity-0" style="animation-delay: 0.1s">
        <div class="relative h-48 bg-linear-to-r from-[#1c2333] to-[#0d1117] flex items-center justify-center">
            <div class="absolute top-4 right-4">
                @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 border border-blue-500/30 text-primary-400">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        Masih Tersedia
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/5 border border-white/10 text-white/45">
                        <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                        Event Selesai
                    </span>
                @endif
            </div>

            <div class="text-center">
                <div class="font-['DM_Serif_Display'] text-5xl font-bold text-primary-400">
                    {{ \Carbon\Carbon::parse($event->event_date)->format('d') }}
                </div>
                <div class="text-base text-white/45">
                    {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('F Y') }}
                </div>
            </div>
        </div>
        
        <div class="p-6 md:p-8">
            <h1 class="font-['DM_Serif_Display'] text-2xl md:text-3xl text-[#f0f4f8] mb-4">
                {{ $event->name }}
            </h1>
            
            <div class="flex flex-wrap gap-4 mb-6 pb-4 border-b border-white/7">
                <div class="flex items-center gap-2 text-white/45 text-sm">
                    <i class="fa-solid fa-calendar-plus text-primary-400"></i>
                    <span>{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y') }}</span>
                </div>

                <div class="flex items-center gap-2 text-white/45 text-sm">
                    <i class="fa-solid fa-users"></i>
                    <span>{{ $event->registrations_count ?? 0 }} peserta terdaftar</span>
                </div>
            </div>
            
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-white/30 uppercase tracking-wide mb-3">
                    <i class="fa-solid fa-circle-info mr-2"></i>
                    Deskripsi Event
                </h3>

                <div class="text-white/45 text-sm leading-relaxed whitespace-pre-wrap">
                    {{ $event->description }}
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3 pt-4 border-t border-white/7">
                @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                    @php
                        $isRegistered = \App\Models\EventRegistration::where('event_id', $event->id)
                            ->where('user_id', auth()->id())
                            ->exists();
                    @endphp
                    
                    @if(!$isRegistered)
                        <a href="{{ route('events.register', $event->id) }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-xl font-semibold text-sm hover:bg-primary-500 hover:-translate-y-px transition-all duration-200 shadow-lg shadow-blue-500/20">
                            <i class="fa-solid fa-user-plus text-xs"></i>
                            Daftar Sekarang
                        </a>
                    @else
                        <div class="inline-flex items-center gap-2 px-6 py-3 bg-[#4ade80]/10 border border-[#4ade80]/20 text-[#4ade80] rounded-xl font-semibold text-sm">
                            <i class="fa-solid fa-circle-check text-xs"></i>
                            Anda Sudah Terdaftar
                        </div>
                    @endif
                @else
                    <div class="inline-flex items-center gap-2 px-6 py-3 bg-white/5 border border-white/10 text-white/45 rounded-xl font-semibold text-sm">
                        <i class="fa-solid fa-ban text-xs"></i>
                        Pendaftaran Ditutup
                    </div>
                @endif
                
                <a href="{{ route('events.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-transparent border border-white/13 text-white/45 rounded-xl font-semibold text-sm hover:border-white/20 hover:text-[#f0f4f8] transition-all duration-200">
                    <i class="fa-solid fa-circle-arrow-left text-xs"></i>
                    Lihat Event Lain
                </a>
            </div>
        </div>
    </div>

</div>

@endsection