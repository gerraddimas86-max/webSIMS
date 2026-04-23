<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Kelola Event - Admin | Community SIMS</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Styles sama seperti sebelumnya (tidak berubah) */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to   { opacity: 1; transform: scale(1); }
        }
        
        .animate-fadeUp {
            animation: fadeUp 0.5s ease forwards;
        }
        
        .animate-modalIn {
            animation: modalFadeIn 0.3s ease forwards;
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
        
        @media (max-width: 768px) {
            .desktop-table { display: none; }
            .mobile-cards { display: block; }
            .event-card {
                background: #161b24;
                border: 1px solid rgba(255,255,255,0.07);
                border-radius: 1rem;
                padding: 1rem;
                margin-bottom: 0.75rem;
                transition: all 0.2s;
            }
            .event-card:hover {
                border-color: rgba(255,255,255,0.13);
                background: #1a1f2a;
            }
            .event-card-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 0.75rem;
            }
            .event-card-title {
                font-weight: 600;
                color: #f0f4f8;
                font-size: 1rem;
                margin-bottom: 0.25rem;
            }
            .event-card-desc {
                font-size: 0.7rem;
                color: rgba(255,255,255,0.3);
                margin-top: 0.25rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .event-card-info {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                margin: 0.75rem 0;
                padding: 0.5rem 0;
                border-top: 1px solid rgba(255,255,255,0.07);
                border-bottom: 1px solid rgba(255,255,255,0.07);
            }
            .event-card-info-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.75rem;
                color: rgba(255,255,255,0.45);
            }
            .event-card-actions {
                display: flex;
                gap: 0.5rem;
                margin-top: 0.75rem;
            }
            .event-card-actions a,
            .event-card-actions button {
                flex: 1;
                justify-content: center;
            }
        }
        
        @media (min-width: 769px) {
            .desktop-table { display: block; }
            .mobile-cards { display: none; }
        }
        
        .admin-table th, .admin-table td { padding: 0.7rem 1rem; }
        @media (max-width: 640px) {
            .admin-table th, .admin-table td { padding: 0.5rem 0.75rem; }
        }
        
        .admin-table th {
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            background: #0d1117;
        }
        
        .admin-table td { border-bottom: 1px solid rgba(255,255,255,0.07); }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: rgba(255,255,255,0.02); }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.25rem;
            flex-wrap: wrap;
        }
        
        .pagination a, .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.875rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .pagination a {
            background: #1c2333;
            border: 1px solid rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.45);
        }
        
        .pagination a:hover {
            background: #242d3d;
            border-color: rgba(255,255,255,0.13);
            color: #f0f4f8;
        }
        
        .pagination .active span {
            background: #2563eb;
            color: white;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="font-['DM_Sans'] bg-[#080c12] text-[#f0f4f8] min-h-screen">

<div id="mobileOverlay" class="overlay"></div>

{{-- Modal Konfirmasi Hapus Event --}}
<div id="deleteModal" class="fixed inset-0 bg-[#080c12]/80 backdrop-blur-md z-50 flex items-center justify-center opacity-0 invisible transition-all duration-300">
    <div class="bg-[#161b24] border border-white/7 rounded-2xl max-w-md w-[90%] p-6 animate-modalIn">
        <div class="text-center">
            <div class="w-14 h-14 rounded-full bg-[#f87171]/10 border border-[#f87171]/20 flex items-center justify-center mx-auto mb-4">
                <i class="fa-regular fa-trash-can text-2xl text-[#f87171]"></i>
            </div>
            <h3 class="font-['DM_Serif_Display'] text-xl text-[#f0f4f8] mb-2">Hapus Event?</h3>
            <p class="text-white/45 text-sm mb-2" id="deleteEventName"></p>
            <p class="text-white/30 text-xs mb-5">Semua data pendaftaran peserta akan ikut terhapus secara permanen.</p>
            <div class="flex gap-3">
                <button id="cancelDeleteBtn" class="flex-1 px-4 py-2 rounded-xl bg-[#1c2333] border border-white/7 text-white/45 font-semibold text-sm hover:bg-[#242d3d] hover:text-[#f0f4f8] transition-all cursor-pointer">
                    <i class="fa-regular fa-circle-xmark mr-1.5 text-[11px]"></i>
                    Batal
                </button>
                <button id="confirmDeleteBtn" class="flex-1 px-4 py-2 rounded-xl bg-[#f87171] text-white font-semibold text-sm hover:bg-[#ef4444] hover:-translate-y-px transition-all cursor-pointer">
                    <i class="fa-regular fa-trash-can mr-1.5 text-[11px]"></i>
                    Hapus Event
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Sidebar --}}
<aside id="adminSidebar" class="sidebar-fixed w-60 bg-[#0d1117] border-r border-white/7 flex flex-col">
    <div class="absolute -top-16 -left-16 w-64 h-64 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.08)_0%,transparent_70%)] pointer-events-none"></div>
    
    {{-- TOMBOL X SAMA PERSIS DENGAN DASHBOARD --}}
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

        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500/10 border-blue-500/18 text-primary-400' : '' }}">
            <i class="fa-solid fa-chart-simple text-[13px]"></i>
            Dashboard
            @if(request()->routeIs('admin.dashboard'))
                <i class="fa-solid fa-circle text-[5px] text-primary-400 ml-auto"></i>
            @endif
        </a>

        <a href="{{ route('admin.events.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150 {{ request()->routeIs('admin.events.index') ? 'bg-blue-500/10 border-blue-500/18 text-primary-400' : '' }}">
            <i class="fa-solid fa-calendar-alt text-[13px]"></i>
            Kelola Event
            @if(request()->routeIs('admin.events.index'))
                <i class="fa-solid fa-circle text-[5px] text-primary-400 ml-auto"></i>
            @endif
        </a>

        <a href="{{ route('admin.events.create') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150 {{ request()->routeIs('admin.events.create') ? 'bg-blue-500/10 border-blue-500/18 text-primary-400' : '' }}">
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

{{-- Main Content --}}
<div class="main-content flex-1 flex flex-col">

    <div class="sticky top-0 z-50 bg-[#0d1117]/85 backdrop-blur-xl border-b border-white/7 px-4 sm:px-8 py-3.5 flex items-center justify-between">
        <div class="absolute -bottom-px left-[10%] right-[10%] h-px bg-linear-to-r from-transparent via-blue-500/25 to-transparent"></div>
        
        <div class="flex items-center gap-3">
            <button id="hamburgerBtn" class="md:hidden flex flex-col justify-center items-center w-9 h-9 rounded-lg border border-white/7 bg-transparent cursor-pointer gap-1.25 hover:bg-white/5 hover:border-white/12 transition-all duration-200">
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full"></span>
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full"></span>
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full"></span>
            </button>
            <span class="font-['DM_Sans'] text-base font-semibold text-[#f0f4f8]">Kelola Event</span>
        </div>
        
        <span class="bg-blue-500/12 border border-blue-500/20 text-primary-400 text-[0.65rem] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap">ADMIN</span>
    </div>

    <div class="flex-1 p-4 sm:p-6 md:p-8">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
            <div>
                <h1 class="font-['DM_Serif_Display'] text-[1.3rem] sm:text-[1.6rem] text-[#f0f4f8] mb-1">Daftar Event</h1>
                <p class="text-[0.8rem] sm:text-[0.875rem] text-white/45">{{ $events->total() }} event terdaftar di sistem</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 bg-primary-600 text-white rounded-xl text-[0.8rem] sm:text-[0.875rem] font-semibold no-underline hover:bg-primary-500 hover:-translate-y-px transition-all shadow-lg shadow-blue-500/20 w-full sm:w-auto justify-center">
                <i class="fa-solid fa-plus text-[12px]"></i>
                Buat Event
            </a>
        </div>

        @if(session('success'))
            <div class="mb-5 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-xl px-4 sm:px-5 py-3 sm:py-3.5 text-[0.8rem] sm:text-[0.875rem] text-[#4ade80] flex items-center gap-2 animate-fadeUp opacity-0" style="animation-delay: 0.08s">
                <i class="fa-solid fa-circle-check text-[14px]"></i>
                <span class="wrap-break-word">{{ session('success') }}</span>
            </div>
        @endif

        {{-- TABEL DESKTOP --}}
        <div class="desktop-table bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden animate-fadeUp opacity-0" style="animation-delay: 0.1s">
            <div class="overflow-x-auto">
                <table class="admin-table w-full border-collapse min-w-150">
                    <thead>
                        <tr>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-[0.7rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30">#</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-[0.7rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30">Nama Event</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-[0.7rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30">Tanggal</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-[0.7rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30">Pendaftar</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-[0.7rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30">Status</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-[0.7rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        <tr class="hover:bg-white/2">
                            <td class="px-3 sm:px-6 py-2 sm:py-3 text-white/45 text-[0.75rem] sm:text-[0.8rem]">{{ $loop->iteration }}</td>
                            <td class="px-3 sm:px-6 py-2 sm:py-3">
                                <div class="font-semibold text-[#f0f4f8] text-sm sm:text-base">{{ $event->name }}</div>
                                <div class="text-[0.7rem] sm:text-[0.78rem] text-white/45 mt-0.5 line-clamp-2">{{ Str::limit($event->description, 50) }}</div>
                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-3 text-white/45 text-xs sm:text-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d M Y') }}</td>
                            <td class="px-3 sm:px-6 py-2 sm:py-3">
                                <span class="font-semibold text-[#f0f4f8] text-sm sm:text-base">{{ $event->registrations_count ?? 0 }}</span>
                                <span class="text-white/45 text-[0.7rem] sm:text-[0.8rem]"> org</span>
                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-3">
                                @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] sm:text-[0.72rem] font-semibold bg-blue-500/10 border border-blue-500/20 text-primary-400 whitespace-nowrap">
                                        <i class="fa-regular fa-clock text-[8px] sm:text-[9px]"></i>
                                        Akan Datang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] sm:text-[0.72rem] font-semibold bg-white/5 border border-white/7 text-white/45 whitespace-nowrap">
                                        <i class="fa-regular fa-circle-check text-[8px] sm:text-[9px]"></i>
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-3">
                                <div class="flex gap-1.5 flex-wrap">
                                    <a href="{{ route('admin.events.participants', $event->id) }}" class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-lg text-[0.7rem] sm:text-[0.75rem] font-semibold bg-primary-600 text-white hover:bg-primary-500 transition-all whitespace-nowrap">
                                        <i class="fa-solid fa-users text-[9px] sm:text-[10px]"></i>
                                        Peserta
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-lg text-[0.7rem] sm:text-[0.75rem] font-semibold bg-transparent border border-white/13 text-white/45 hover:bg-[#1c2333] hover:text-[#f0f4f8] transition-all whitespace-nowrap">
                                        <i class="fa-regular fa-pen-to-square text-[9px] sm:text-[10px]"></i>
                                        Edit
                                    </a>
                                    <button onclick="showDeleteModal({{ $event->id }}, '{{ addslashes($event->name) }}')" class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-lg text-[0.7rem] sm:text-[0.75rem] font-semibold bg-[#f87171]/10 border border-[#f87171]/20 text-[#f87171] hover:bg-[#f87171] hover:text-white transition-all cursor-pointer whitespace-nowrap">
                                        <i class="fa-regular fa-trash-can text-[9px] sm:text-[10px]"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 sm:py-16 text-white/45">Belum ada event yang dibuat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MOBILE CARD LAYOUT --}}
        <div class="mobile-cards animate-fadeUp opacity-0" style="animation-delay: 0.1s">
            @forelse($events as $event)
            <div class="event-card">
                <div class="event-card-header">
                    <div>
                        <div class="event-card-title">{{ $event->name }}</div>
                        <div class="event-card-desc">{{ Str::limit($event->description, 70) }}</div>
                    </div>
                    @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-blue-500/10 border border-blue-500/20 text-primary-400 whitespace-nowrap">
                            <i class="fa-regular fa-clock text-[8px]"></i>
                            Akan Datang
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-white/5 border border-white/7 text-white/45 whitespace-nowrap">
                            <i class="fa-regular fa-circle-check text-[8px]"></i>
                            Selesai
                        </span>
                    @endif
                </div>
                
                <div class="event-card-info">
                    <div class="event-card-info-item">
                        <i class="fa-regular fa-calendar-alt text-[11px]"></i>
                        {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d M Y') }}
                    </div>
                    <div class="event-card-info-item">
                        <i class="fa-solid fa-users text-[11px]"></i>
                        {{ $event->registrations_count ?? 0 }} pendaftar
                    </div>
                    <div class="event-card-info-item">
                        <i class="fa-regular fa-hashtag text-[11px]"></i>
                        ID: {{ $event->id }}
                    </div>
                </div>
                
                <div class="event-card-actions">
                    <a href="{{ route('admin.events.participants', $event->id) }}" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg text-[0.7rem] font-semibold bg-primary-600 text-white hover:bg-primary-500 transition-all">
                        <i class="fa-solid fa-users text-[9px]"></i>
                        Peserta
                    </a>
                    <a href="{{ route('admin.events.edit', $event->id) }}" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg text-[0.7rem] font-semibold bg-transparent border border-white/13 text-white/45 hover:bg-[#1c2333] hover:text-[#f0f4f8] transition-all">
                        <i class="fa-regular fa-pen-to-square text-[9px]"></i>
                        Edit
                    </a>
                    <button onclick="showDeleteModal({{ $event->id }}, '{{ addslashes($event->name) }}')" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg text-[0.7rem] font-semibold bg-[#f87171]/10 border border-[#f87171]/20 text-[#f87171] hover:bg-[#f87171] hover:text-white transition-all cursor-pointer">
                        <i class="fa-regular fa-trash-can text-[9px]"></i>
                        Hapus
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="flex flex-col items-center gap-3">
                    <i class="fa-regular fa-calendar-xmark text-3xl text-white/22"></i>
                    <p class="text-white/45 text-sm">Belum ada event yang dibuat.</p>
                    <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-semibold no-underline hover:bg-primary-500 transition-all">
                        <i class="fa-solid fa-plus text-[12px]"></i>
                        Buat Event Pertama
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        @if($events->hasPages())
            <div class="mt-5 overflow-x-auto">
                {{ $events->links('pagination::tailwind') }}
            </div>
        @endif

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
    
    // Delete modal
    let deleteEventId = null;
    let deleteForm = null;
    
    window.showDeleteModal = function(eventId, eventName) {
        deleteEventId = eventId;
        document.getElementById('deleteEventName').innerHTML = '<strong class="text-[#f0f4f8]">"' + eventName + '"</strong>';
        deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = '/admin/events/' + eventId;
        deleteForm.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                               '<input type="hidden" name="_method" value="DELETE">';
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');
    };
    
    function hideModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('opacity-100', 'visible');
        modal.classList.add('opacity-0', 'invisible');
        deleteEventId = null;
        deleteForm = null;
    }
    
    function confirmDelete() {
        if (deleteForm && deleteEventId) {
            document.body.appendChild(deleteForm);
            deleteForm.submit();
        }
        hideModal();
    }
    
    document.getElementById('cancelDeleteBtn')?.addEventListener('click', hideModal);
    document.getElementById('confirmDeleteBtn')?.addEventListener('click', confirmDelete);
    
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) hideModal();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('deleteModal');
            if (modal && modal.classList.contains('opacity-100')) hideModal();
        }
    });
</script>

</body>
</html>