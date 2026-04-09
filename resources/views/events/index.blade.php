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

<div class="w-full max-w-6xl mx-auto px-4 py-8 font-['DM_Sans'] text-[#f0f4f8]">
    
    {{-- Header --}}
    <div class="text-center mb-12 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
        <h1 class="font-['DM_Serif_Display'] text-3xl md:text-4xl text-[#f0f4f8] mb-3">
            Event & Kegiatan
        </h1>
        <p class="text-white/45 text-sm md:text-base max-w-2xl mx-auto">
            Ikuti berbagai event menarik dari Community SIMS. 
            Tingkatkan skill, perluas jaringan, dan raih prestasi bersama kami.
        </p>
    </div>

    {{-- Filter / Search --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8 animate-fadeUp opacity-0" style="animation-delay: 0.1s">
        <div class="relative w-full sm:w-80">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/22 text-sm"></i>
            <input type="text" id="searchInput" oninput="filterEvents()" 
                   placeholder="Cari event..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-[#1c2333] border border-white/7 rounded-xl text-sm text-[#f0f4f8] placeholder-white/22 outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all">
        </div>
        <div class="flex gap-2">
            <button onclick="filterUpcoming()" id="btnUpcoming" class="px-4 py-2 rounded-xl text-sm font-medium bg-[#1c2333] border border-white/7 text-white/45 hover:bg-[#242d3d] hover:text-[#f0f4f8] transition-all">
                <i class="fa-regular fa-clock mr-1.5"></i>Akan Datang
            </button>
            <button onclick="filterAll()" id="btnAll" class="px-4 py-2 rounded-xl text-sm font-medium bg-primary-600 text-white border border-blue-500/30 hover:bg-primary-500 transition-all">
                <i class="fa-solid fa-list mr-1.5"></i>Semua
            </button>
        </div>
    </div>

    {{-- Events Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="eventsGrid">
        @forelse($events as $event)
        <div class="event-card bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden hover:border-white/13 hover:shadow-[0_8px_28px_rgba(0,0,0,0.3)] transition-all duration-300 animate-fadeUp opacity-0 group"
             data-event-date="{{ $event->event_date }}"
             style="animation-delay: {{ 0.1 + ($loop->index * 0.03) }}s">
            
            {{-- Event Date Badge --}}
            <div class="relative h-32 bg-linear-to-br from-[#1c2333] to-[#0d1117] flex items-center justify-center">
                <div class="absolute top-3 right-3">
                    @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.7rem] font-semibold bg-blue-500/20 border border-blue-500/30 text-primary-400">
                            <i class="fa-regular fa-clock text-[9px]"></i>
                            Akan Datang
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.7rem] font-semibold bg-white/5 border border-white/10 text-white/45">
                            <i class="fa-regular fa-check-circle text-[9px]"></i>
                            Selesai
                        </span>
                    @endif
                </div>
                <div class="text-center">
                    <div class="font-['DM_Serif_Display'] text-4xl font-bold text-primary-400">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('d') }}
                    </div>
                    <div class="text-sm text-white/45">
                        {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('F Y') }}
                    </div>
                </div>
            </div>
            
            {{-- Event Content --}}
            <div class="p-5">
                <h3 class="font-['DM_Serif_Display'] text-lg font-bold text-[#f0f4f8] mb-2 line-clamp-2">
                    {{ $event->name }}
                </h3>
                <p class="text-white/45 text-sm leading-relaxed mb-4 line-clamp-3">
                    {{ Str::limit($event->description, 100) }}
                </p>
                
                <div class="flex items-center justify-between pt-3 border-t border-white/7">
                    <div class="flex items-center gap-1.5 text-white/30 text-xs">
                        <i class="fa-regular fa-user"></i>
                        <span>{{ $event->registrations_count ?? 0 }} pendaftar</span>
                    </div>
                    <a href="{{ route('events.show', $event->id) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-500/10 border border-blue-500/20 text-primary-400 hover:bg-blue-500/20 hover:-translate-y-px transition-all duration-200">
                        Lihat Detail
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <div class="flex flex-col items-center gap-3">
                <i class="fa-regular fa-calendar-xmark text-5xl text-white/22"></i>
                <p class="text-white/45 text-sm">Belum ada event yang tersedia saat ini.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($events->hasPages())
        <div class="mt-8">
            {{ $events->links('pagination::tailwind') }}
        </div>
    @endif
</div>

<script>
    let currentFilter = 'all';
    
    function filterEvents() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.event-card');
        
        cards.forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const description = card.querySelector('p')?.textContent.toLowerCase() || '';
            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            
            if (currentFilter === 'upcoming') {
                const eventDate = card.dataset.eventDate;
                const isUpcoming = new Date(eventDate) > new Date();
                card.style.display = (matchesSearch && isUpcoming) ? '' : 'none';
            } else {
                card.style.display = matchesSearch ? '' : 'none';
            }
        });
    }
    
    function filterUpcoming() {
        currentFilter = 'upcoming';
        const cards = document.querySelectorAll('.event-card');
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        cards.forEach(card => {
            const eventDate = new Date(card.dataset.eventDate);
            card.style.display = eventDate >= today ? '' : 'none';
        });
        
        document.getElementById('btnUpcoming').classList.add('bg-[#2563eb]', 'text-white');
        document.getElementById('btnUpcoming').classList.remove('bg-[#1c2333]', 'text-white/45');
        document.getElementById('btnAll').classList.add('bg-[#1c2333]', 'text-white/45');
        document.getElementById('btnAll').classList.remove('bg-[#2563eb]', 'text-white');
        
        document.getElementById('searchInput').value = '';
    }
    
    function filterAll() {
        currentFilter = 'all';
        const cards = document.querySelectorAll('.event-card');
        cards.forEach(card => {
            card.style.display = '';
        });
        
        document.getElementById('btnAll').classList.add('bg-[#2563eb]', 'text-white');
        document.getElementById('btnAll').classList.remove('bg-[#1c2333]', 'text-white/45');
        document.getElementById('btnUpcoming').classList.add('bg-[#1c2333]', 'text-white/45');
        document.getElementById('btnUpcoming').classList.remove('bg-[#2563eb]', 'text-white');
        
        document.getElementById('searchInput').value = '';
        filterEvents();
    }
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

@endsection