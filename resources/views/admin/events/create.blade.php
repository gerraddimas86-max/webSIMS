<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ isset($event) ? 'Edit Event' : 'Buat Event' }} - Admin | Community SIMS</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeUp {
            animation: fadeUp 0.5s ease forwards;
        }
        
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            z-index: 100;
            transition: transform 0.3s ease-in-out;
        }
        
        .main-content {
            margin-left: 280px;
            transition: margin-left 0.3s ease-in-out;
        }
        
        @media (max-width: 768px) {
            .sidebar-fixed {
                transform: translateX(-100%);
            }
            .sidebar-fixed.mobile-open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .overlay {
                position: fixed;
                inset: 0;
                background: rgba(8, 12, 18, 0.7);
                backdrop-filter: blur(4px);
                z-index: 99;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }
            .overlay.active {
                opacity: 1;
                visibility: visible;
            }
        }
        
        @media (min-width: 769px) and (max-width: 1024px) {
            .sidebar-fixed { width: 240px; }
            .main-content { margin-left: 240px; }
        }
    </style>
</head>
<body class="font-['DM_Sans'] bg-[#080c12] text-[#f0f4f8] min-h-screen">

{{-- Overlay untuk mobile --}}
<div id="mobileOverlay" class="overlay"></div>

{{-- ── Sidebar ──────────────────────────────────────────────── --}}
<aside id="adminSidebar" class="sidebar-fixed w-60 md:w-60 bg-[#0d1117] border-r border-white/7 flex flex-col">
    <div class="absolute -top-16 -left-16 w-64 h-64 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.08)_0%,transparent_70%)] pointer-events-none"></div>
    
    <button id="closeSidebarBtn" class="md:hidden absolute right-3 top-3 text-white/45 hover:text-white transition-colors p-2 z-10">
        <i class="fa-solid fa-xmark text-xl"></i>
    </button>
    
    <div class="px-5 pt-6 pb-4.5 border-b border-white/7 relative">
        <div class="flex items-center gap-1.5 text-[0.62rem] tracking-[0.14em] uppercase text-white/22 mb-1.5">
            <i class="fa-solid fa-circle text-[5px] text-primary-400"></i>
            <span>Panel Kontrol</span>
        </div>
        <div class="font-['DM_Sans'] text-[0.95rem] font-bold text-[#f0f4f8] tracking-wide">Community SIMS</div>
    </div>

    <nav class="flex-1 px-2.5 py-4 flex flex-col gap-0.5">
        <div class="text-[0.6rem] tracking-[0.12em] uppercase text-white/22 px-3 pt-2.5 pb-1">Menu</div>

        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500/10 border-blue-500/18 text-primary-400' : '' }}">
            <i class="fa-solid fa-chart-simple text-[13px]"></i>
            Dashboard
            @if(request()->routeIs('admin.dashboard'))
                <i class="fa-solid fa-circle text-[5px] text-primary-400 ml-auto"></i>
            @endif
        </a>

        <a href="{{ route('admin.events.index') }}" 
           class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150 {{ request()->routeIs('admin.events.index') ? 'bg-blue-500/10 border-blue-500/18 text-primary-400' : '' }}">
            <i class="fa-solid fa-calendar-alt text-[13px]"></i>
            Kelola Event
            @if(request()->routeIs('admin.events.index'))
                <i class="fa-solid fa-circle text-[5px] text-primary-400 ml-auto"></i>
            @endif
        </a>

        <a href="{{ route('admin.events.create') }}" 
           class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150 {{ request()->routeIs('admin.events.create') ? 'bg-blue-500/10 border-blue-500/18 text-primary-400' : '' }}">
            <i class="fa-solid fa-plus-circle text-[13px]"></i>
            Buat Event
            @if(request()->routeIs('admin.events.create'))
                <i class="fa-solid fa-circle text-[5px] text-primary-400 ml-auto"></i>
            @endif
        </a>
    </nav>

    <div class="px-2.5 py-3.5 pb-5 border-t border-white/7">
        <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl bg-[#1c2333] border border-white/7">
            <div class="w-7.5 h-7.5 rounded-lg bg-blue-500/15 border border-blue-500/20 flex items-center justify-center text-[0.72rem] font-bold text-primary-400 shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-[0.78rem] font-semibold text-[#f0f4f8] truncate">{{ Auth::user()->name }}</div>
                <div class="text-[0.65rem] text-white/22">Administrator</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-none border-none cursor-pointer text-white/22 hover:text-[#f87171] transition-colors p-1">
                    <i class="fa-solid fa-sign-out-alt text-[13px]"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ── Main ─────────────────────────────────────────────────── --}}
<div class="main-content flex-1 flex flex-col">

    {{-- Topbar dengan Hamburger --}}
    <div class="sticky top-0 z-50 bg-[#0d1117]/85 backdrop-blur-xl border-b border-white/7 px-4 sm:px-8 py-3.5 flex items-center justify-between">
        <div class="absolute -bottom-px left-[10%] right-[10%] h-px bg-linear-to-r from-transparent via-blue-500/25 to-transparent"></div>
        
        <div class="flex items-center gap-3">
            <button id="hamburgerBtn" class="md:hidden flex flex-col justify-center items-center w-9 h-9 rounded-lg border border-white/7 bg-transparent cursor-pointer gap-1.25 hover:bg-white/5 hover:border-white/12 transition-all duration-200">
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full"></span>
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full"></span>
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full"></span>
            </button>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.events.index') }}" class="flex items-center gap-1.5 text-[0.82rem] text-white/45 no-underline hover:text-[#f0f4f8] transition-colors">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                <span class="text-white/22 hidden sm:inline">/</span>
                <span class="font-['DM_Sans'] text-base font-semibold text-[#f0f4f8]">{{ isset($event) ? 'Edit Event' : 'Buat Event' }}</span>
            </div>
        </div>
        
        <span class="bg-blue-500/12 border border-blue-500/20 text-primary-400 text-[0.65rem] font-bold px-2.5 py-0.5 rounded-full tracking-wide whitespace-nowrap">ADMIN</span>
    </div>

    <div class="flex-1 p-4 sm:p-6 md:p-8 flex justify-center">
        <div class="w-full max-w-2xl">

            {{-- Form Card --}}
            <div class="bg-[#161b24] border border-white/7 rounded-2xl p-4 sm:p-6 md:p-8 animate-fadeUp opacity-0" style="animation-delay: 0.1s">
                <h2 class="font-['DM_Serif_Display'] text-[1.1rem] font-bold text-[#f0f4f8] mb-1">{{ isset($event) ? 'Edit Event' : 'Event Baru' }}</h2>
                <p class="text-[0.8rem] sm:text-[0.85rem] text-white/45 mb-6 sm:mb-8">{{ isset($event) ? 'Perbarui detail event' : 'Isi detail event yang akan diselenggarakan' }}</p>

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="mb-6 bg-[#f87171]/10 border border-[#f87171]/20 rounded-xl px-4 py-3 text-[0.85rem] text-[#f87171]">
                        <ul class="list-disc list-inside flex flex-col gap-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form dengan action yang berbeda untuk CREATE dan EDIT --}}
                <form method="POST" action="{{ isset($event) ? route('admin.events.update', $event->id) : route('admin.events.store') }}">
                    @csrf
                    @if(isset($event))
                        @method('PUT')
                    @endif

                    {{-- Nama Event --}}
                    <div class="mb-5">
                        <label class="block text-[0.7rem] sm:text-[0.75rem] font-semibold text-white/45 uppercase tracking-wide mb-2">Nama Event</label>
                        <input type="text" name="name" value="{{ old('name', $event->name ?? '') }}"
                               class="w-full px-4 py-3 bg-[#1c2333] border border-white/7 rounded-xl font-['DM_Sans'] text-[0.85rem] sm:text-[0.9rem] text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all {{ $errors->has('name') ? 'border-[#f87171]' : '' }}"
                               placeholder="Contoh: Workshop UI/UX Design 2025" required>
                        @error('name') 
                            <div class="text-[0.72rem] sm:text-[0.78rem] text-[#f87171] mt-1.5">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-5">
                        <label class="block text-[0.7rem] sm:text-[0.75rem] font-semibold text-white/45 uppercase tracking-wide mb-2">Deskripsi</label>
                        <textarea name="description" rows="4"
                               class="w-full px-4 py-3 bg-[#1c2333] border border-white/7 rounded-xl font-['DM_Sans'] text-[0.85rem] sm:text-[0.9rem] text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all resize-vertical min-h-25 {{ $errors->has('description') ? 'border-[#f87171]' : '' }}"
                               placeholder="Deskripsikan event secara singkat..." required>{{ old('description', $event->description ?? '') }}</textarea>
                        @error('description') 
                            <div class="text-[0.72rem] sm:text-[0.78rem] text-[#f87171] mt-1.5">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Tanggal Event --}}
                    <div class="mb-6">
                        <label class="block text-[0.7rem] sm:text-[0.75rem] font-semibold text-white/45 uppercase tracking-wide mb-2">Tanggal Event</label>
                        <input type="date" name="event_date" value="{{ old('event_date', isset($event) ? $event->event_date->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-3 bg-[#1c2333] border border-white/7 rounded-xl font-['DM_Sans'] text-[0.85rem] sm:text-[0.9rem] text-[#f0f4f8] outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all {{ $errors->has('event_date') ? 'border-[#f87171]' : '' }}"
                               {{ !isset($event) ? 'min=' . date('Y-m-d') : '' }} required>
                        @error('event_date') 
                            <div class="text-[0.72rem] sm:text-[0.78rem] text-[#f87171] mt-1.5">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 mt-6 pt-6 border-t border-white/7">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary-600 text-white rounded-xl font-['DM_Sans'] text-[0.85rem] sm:text-[0.875rem] font-semibold cursor-pointer hover:bg-primary-500 hover:-translate-y-px transition-all duration-200 shadow-lg shadow-blue-500/20">
                            <i class="fa-regular fa-floppy-disk text-[12px]"></i>
                            {{ isset($event) ? 'Simpan Perubahan' : 'Simpan Event' }}
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-transparent border border-white/13 rounded-xl font-['DM_Sans'] text-[0.85rem] sm:text-[0.875rem] font-semibold text-white/45 no-underline hover:border-white/20 hover:text-[#f0f4f8] transition-all duration-200">
                            <i class="fa-regular fa-circle-xmark text-[12px]"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    // Mobile sidebar toggle
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('adminSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const overlay = document.getElementById('mobileOverlay');
    
    function openSidebar() {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeSidebar() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (hamburgerBtn) hamburgerBtn.addEventListener('click', openSidebar);
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);
    
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) closeSidebar();
    });
</script>

</body>
</html>