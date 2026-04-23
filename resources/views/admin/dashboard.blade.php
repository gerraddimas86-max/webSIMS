<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Admin Dashboard - Community SIMS</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* same styles as before */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
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
        
        .admin-table th, .admin-table td { padding: 0.7rem 1rem; }
        @media (max-width: 640px) {
            .admin-table th, .admin-table td { padding: 0.5rem 0.75rem; }
        }
        .admin-table th { text-align: left; border-bottom: 1px solid rgba(255,255,255,0.07); background: #0d1117; }
        .admin-table td { border-bottom: 1px solid rgba(255,255,255,0.07); }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: rgba(255,255,255,0.02); }
        
        @media (max-width: 768px) {
            .desktop-table { display: none; }
            .mobile-cards { display: block; }
            .recent-event-card {
                background: #161b24;
                border: 1px solid rgba(255,255,255,0.07);
                border-radius: 1rem;
                padding: 1rem;
                margin-bottom: 0.75rem;
            }
            .recent-event-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; }
            .recent-event-name { font-weight: 600; color: #f0f4f8; font-size: 0.95rem; }
            .recent-event-info { display: flex; flex-wrap: wrap; gap: 1rem; margin: 0.75rem 0; padding: 0.5rem 0; border-top: 1px solid rgba(255,255,255,0.07); border-bottom: 1px solid rgba(255,255,255,0.07); }
            .recent-event-info-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.7rem; color: rgba(255,255,255,0.45); }
            .recent-event-actions { display: flex; gap: 0.5rem; margin-top: 0.75rem; }
            .recent-event-actions a { flex: 1; justify-content: center; }
        }
        
        @media (min-width: 769px) {
            .desktop-table { display: block; }
            .mobile-cards { display: none; }
        }
    </style>
</head>
<body class="font-['DM_Sans'] bg-[#080c12] text-[#f0f4f8] min-h-screen">

<div id="mobileOverlay" class="overlay"></div>

{{-- ── Sidebar ──────────────────────────────────────────────── --}}
<aside id="adminSidebar" class="sidebar-fixed w-60 md:w-60 bg-[#0d1117] border-r border-white/7 flex flex-col">
    <div class="absolute -top-16 -left-16 w-64 h-64 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.08)_0%,transparent_70%)] pointer-events-none"></div>
    
    {{-- TOMBOL X UNTUK MOBILE --}}
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

    <div class="sticky top-0 z-50 bg-[#0d1117]/85 backdrop-blur-xl border-b border-white/7 px-4 sm:px-8 py-3.5 flex items-center justify-between">
        <div class="absolute -bottom-px left-[10%] right-[10%] h-px bg-linear-to-r from-transparent via-blue-500/25 to-transparent"></div>
        
        <div class="flex items-center gap-3">
            <button id="hamburgerBtn" class="md:hidden flex flex-col justify-center items-center w-9 h-9 rounded-lg border border-white/7 bg-transparent cursor-pointer gap-1.25 hover:bg-white/5 hover:border-white/12 transition-all duration-200">
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full"></span>
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full"></span>
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full"></span>
            </button>
            <span class="font-['DM_Sans'] text-base font-semibold text-[#f0f4f8]">Dashboard</span>
        </div>
        
        <div class="flex items-center gap-3.5">
            <span class="text-[0.7rem] sm:text-[0.75rem] text-white/22 hidden sm:inline">{{ now()->translatedFormat('l, d F Y') }}</span>
            <span class="bg-blue-500/12 border border-blue-500/20 text-primary-400 text-[0.65rem] font-bold px-2.5 py-0.5 rounded-full tracking-wide whitespace-nowrap">ADMIN</span>
        </div>
    </div>

    <div class="flex-1 p-4 sm:p-6 md:p-8">

        <div class="mb-6 sm:mb-8 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
            <h1 class="font-['DM_Serif_Display'] text-[1.3rem] sm:text-[1.65rem] text-[#f0f4f8] mb-1">Selamat datang, {{ Auth::user()->name }} 👋</h1>
            <p class="text-[0.8rem] sm:text-[0.875rem] text-white/45">Kelola event dan pantau pendaftaran komunitas dari sini.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-7">
            <div class="bg-[#161b24] border border-white/7 rounded-2xl p-4 sm:p-5 relative overflow-hidden hover:border-white/13 hover:shadow-[0_8px_24px_rgba(0,0,0,0.3)] transition-all animate-fadeUp opacity-0" style="animation-delay: 0.1s">
                <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-[radial-gradient(circle,rgba(251,191,36,0.08)_0%,transparent_70%)] pointer-events-none"></div>
                <div class="w-9 h-9 rounded-lg bg-[#fbbf24]/10 border border-[#fbbf24]/18 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-calendar-alt text-[#fbbf24] text-sm"></i>
                </div>
                <div class="font-['DM_Serif_Display'] text-[1.8rem] sm:text-[2.1rem] text-[#f0f4f8] leading-none mb-1">{{ number_format($totalEvents) }}</div>
                <div class="text-[0.68rem] font-bold text-white/22 uppercase tracking-widest mb-1.5">Total Event</div>
                <div class="text-[0.7rem] sm:text-[0.75rem] text-white/45">{{ $upcomingEvents }} akan datang</div>
            </div>

            <div class="bg-[#161b24] border border-white/7 rounded-2xl p-4 sm:p-5 relative overflow-hidden hover:border-white/13 hover:shadow-[0_8px_24px_rgba(0,0,0,0.3)] transition-all animate-fadeUp opacity-0" style="animation-delay: 0.17s">
                <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.08)_0%,transparent_70%)] pointer-events-none"></div>
                <div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/18 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-users text-primary-400 text-sm"></i>
                </div>
                <div class="font-['DM_Serif_Display'] text-[1.8rem] sm:text-[2.1rem] text-[#f0f4f8] leading-none mb-1">{{ number_format($totalUsers) }}</div>
                <div class="text-[0.68rem] font-bold text-white/22 uppercase tracking-widest mb-1.5">Total User</div>
                <div class="text-[0.7rem] sm:text-[0.75rem] text-white/45">+{{ $newUsersThisMonth }} bulan ini</div>
            </div>

            <div class="bg-[#161b24] border border-white/7 rounded-2xl p-4 sm:p-5 relative overflow-hidden hover:border-white/13 hover:shadow-[0_8px_24px_rgba(0,0,0,0.3)] transition-all animate-fadeUp opacity-0" style="animation-delay: 0.24s">
                <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-[radial-gradient(circle,rgba(74,222,128,0.08)_0%,transparent_70%)] pointer-events-none"></div>
                <div class="w-9 h-9 rounded-lg bg-[#4ade80]/10 border border-[#4ade80]/18 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-user-check text-[#4ade80] text-sm"></i>
                </div>
                <div class="font-['DM_Serif_Display'] text-[1.8rem] sm:text-[2.1rem] text-[#f0f4f8] leading-none mb-1">{{ number_format($totalRegistrations) }}</div>
                <div class="text-[0.68rem] font-bold text-white/22 uppercase tracking-widest mb-1.5">Total Pendaftar</div>
                <div class="text-[0.7rem] sm:text-[0.75rem] text-white/45">Dari semua event</div>
            </div>
        </div>

        <div class="mb-7 animate-fadeUp opacity-0" style="animation-delay: 0.32s">
            <div class="flex items-center gap-2 mb-3.5">
                <div class="text-[0.68rem] font-bold tracking-widest uppercase text-white/22 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-[10px]"></i>
                    Aksi Cepat
                </div>
                <div class="flex-1 h-px bg-white/7"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                <a href="{{ route('admin.events.create') }}" class="bg-[#161b24] border border-white/7 rounded-2xl p-4 sm:p-5 flex items-center gap-3.5 no-underline text-[#f0f4f8] hover:border-white/13 hover:bg-[#1c2333] hover:-translate-y-0.5 hover:shadow-[0_6px_20px_rgba(0,0,0,0.3)] transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#1c2333] flex items-center justify-center shrink-0 hover:bg-[#242d3d] transition-all">
                        <i class="fa-solid fa-plus-circle text-[#fbbf24] text-lg"></i>
                    </div>
                    <div>
                        <strong class="block text-[0.85rem] font-semibold text-[#f0f4f8] mb-0.5">Buat Event Baru</strong>
                        <span class="text-[0.7rem] sm:text-[0.75rem] text-white/45">Tambahkan event untuk komunitas</span>
                    </div>
                </a>

                <a href="{{ route('admin.events.index') }}" class="bg-[#161b24] border border-white/7 rounded-2xl p-4 sm:p-5 flex items-center gap-3.5 no-underline text-[#f0f4f8] hover:border-white/13 hover:bg-[#1c2333] hover:-translate-y-0.5 hover:shadow-[0_6px_20px_rgba(0,0,0,0.3)] transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#1c2333] flex items-center justify-center shrink-0 hover:bg-[#242d3d] transition-all">
                        <i class="fa-solid fa-calendar-alt text-primary-400 text-lg"></i>
                    </div>
                    <div>
                        <strong class="block text-[0.85rem] font-semibold text-[#f0f4f8] mb-0.5">Lihat Semua Event</strong>
                        <span class="text-[0.7rem] sm:text-[0.75rem] text-white/45">Kelola & pantau event aktif</span>
                    </div>
                </a>

                <a href="{{ route('admin.events.index') }}#registrations" class="bg-[#161b24] border border-white/7 rounded-2xl p-4 sm:p-5 flex items-center gap-3.5 no-underline text-[#f0f4f8] hover:border-white/13 hover:bg-[#1c2333] hover:-translate-y-0.5 hover:shadow-[0_6px_20px_rgba(0,0,0,0.3)] transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#1c2333] flex items-center justify-center shrink-0 hover:bg-[#242d3d] transition-all">
                        <i class="fa-solid fa-users text-[#4ade80] text-lg"></i>
                    </div>
                    <div>
                        <strong class="block text-[0.85rem] font-semibold text-[#f0f4f8] mb-0.5">Data Peserta</strong>
                        <span class="text-[0.7rem] sm:text-[0.75rem] text-white/45">Lihat pendaftar per event</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden animate-fadeUp opacity-0" style="animation-delay: 0.42s">
            <div class="px-4 sm:px-6 py-3 sm:py-4.5 border-b border-white/7 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span class="text-[0.85rem] font-semibold text-[#f0f4f8]">Event Terbaru</span>
                <a href="{{ route('admin.events.index') }}" class="text-[0.7rem] sm:text-[0.75rem] text-primary-400 no-underline hover:text-[#f0f4f8] transition-colors">Lihat semua →</a>
            </div>
            
            <div class="desktop-table overflow-x-auto">
                <table class="admin-table w-full border-collapse min-w-125">
                    <thead>
                        <tr>
                            <th class="text-[0.65rem] font-bold uppercase tracking-[0.07em] text-white/30 px-3 sm:px-6 py-2 sm:py-3">Nama Event</th>
                            <th class="text-[0.65rem] font-bold uppercase tracking-[0.07em] text-white/30 px-3 sm:px-6 py-2 sm:py-3">Tanggal</th>
                            <th class="text-[0.65rem] font-bold uppercase tracking-[0.07em] text-white/30 px-3 sm:px-6 py-2 sm:py-3">Pendaftar</th>
                            <th class="text-[0.65rem] font-bold uppercase tracking-[0.07em] text-white/30 px-3 sm:px-6 py-2 sm:py-3">Status</th>
                            <th class="text-[0.65rem] font-bold uppercase tracking-[0.07em] text-white/30 px-3 sm:px-6 py-2 sm:py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentEvents as $event)
                        <tr class="hover:bg-white/2 transition-colors">
                            <td class="font-semibold text-[#f0f4f8] px-3 sm:px-6 py-2 sm:py-3 text-sm">{{ $event->name }}</td>
                            <td class="text-white/45 px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</td>
                            <td class="text-white/45 px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm">{{ $event->registrations_count ?? 0 }} orang</td>
                            <td class="px-3 sm:px-6 py-2 sm:py-3">
                                @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] sm:text-[0.68rem] font-semibold bg-blue-500/10 border border-blue-500/20 text-primary-400 whitespace-nowrap">
                                        <i class="fa-regular fa-clock mr-1 text-[8px] sm:text-[9px]"></i>
                                        Akan Datang
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] sm:text-[0.68rem] font-semibold bg-white/5 border border-white/7 text-white/22 whitespace-nowrap">
                                        <i class="fa-regular fa-check-circle mr-1 text-[8px] sm:text-[9px]"></i>
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-3">
                                <div class="flex gap-1.5">
                                    <a href="{{ route('admin.events.participants', $event->id) }}" class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-lg text-[0.7rem] sm:text-[0.75rem] font-semibold bg-primary-600 text-white hover:bg-primary-500 transition-all whitespace-nowrap">
                                        <i class="fa-solid fa-users text-[9px] sm:text-[10px]"></i>
                                        Peserta
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-lg text-[0.7rem] sm:text-[0.75rem] font-semibold bg-transparent border border-white/13 text-white/45 hover:bg-[#1c2333] hover:text-[#f0f4f8] hover:border-white/13 transition-all whitespace-nowrap">
                                        <i class="fa-regular fa-pen-to-square text-[9px] sm:text-[10px]"></i>
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-white/22 py-10">
                                <i class="fa-regular fa-calendar-xmark text-2xl mb-2 block"></i>
                                Belum ada event.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-cards p-4">
                @forelse($recentEvents as $event)
                <div class="recent-event-card">
                    <div class="recent-event-header">
                        <div>
                            <div class="recent-event-name">{{ $event->name }}</div>
                        </div>
                        @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-blue-500/10 border border-blue-500/20 text-primary-400 whitespace-nowrap">
                                <i class="fa-regular fa-clock text-[8px]"></i>
                                Akan Datang
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-white/5 border border-white/7 text-white/30 whitespace-nowrap">
                                <i class="fa-regular fa-check-circle text-[8px]"></i>
                                Selesai
                            </span>
                        @endif
                    </div>
                    
                    <div class="recent-event-info">
                        <div class="recent-event-info-item">
                            <i class="fa-regular fa-calendar-alt text-[10px]"></i>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                        </div>
                        <div class="recent-event-info-item">
                            <i class="fa-solid fa-users text-[10px]"></i>
                            {{ $event->registrations_count ?? 0 }} pendaftar
                        </div>
                    </div>
                    
                    <div class="recent-event-actions">
                        <a href="{{ route('admin.events.participants', $event->id) }}" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg text-[0.7rem] font-semibold bg-primary-600 text-white hover:bg-primary-500 transition-all">
                            <i class="fa-solid fa-users text-[9px]"></i>
                            Peserta
                        </a>
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg text-[0.7rem] font-semibold bg-transparent border border-white/13 text-white/45 hover:bg-[#1c2333] hover:text-[#f0f4f8] transition-all">
                            <i class="fa-regular fa-pen-to-square text-[9px]"></i>
                            Edit
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fa-regular fa-calendar-xmark text-2xl text-white/22 mb-2 block"></i>
                    <p class="text-white/45 text-sm">Belum ada event.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('mobileOverlay');
    
    function closeSidebar() {
        sidebar?.classList.remove('mobile-open');
        overlay?.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function openSidebar() {
        sidebar?.classList.add('mobile-open');
        overlay?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    if (hamburgerBtn) hamburgerBtn.addEventListener('click', openSidebar);
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);
    
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) closeSidebar();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar?.classList.contains('mobile-open')) closeSidebar();
    });
</script>

</body>
</html>