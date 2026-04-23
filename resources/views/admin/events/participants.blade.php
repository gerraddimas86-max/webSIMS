<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Peserta Event - Admin | Community SIMS</title>
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
        
        /* Desktop table styles */
        .admin-table th,
        .admin-table td {
            padding: 0.7rem 0.75rem;
        }
        
        @media (min-width: 640px) {
            .admin-table th,
            .admin-table td {
                padding: 0.75rem 1rem;
            }
        }
        
        .admin-table th {
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            background: #0d1117;
        }
        
        .admin-table td {
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        
        .admin-table tr:hover td {
            background: rgba(255,255,255,0.02);
        }
        
        /* Mobile card layout */
        @media (max-width: 768px) {
            .desktop-table {
                display: none;
            }
            .mobile-cards {
                display: block;
            }
            .participant-card {
                background: #161b24;
                border: 1px solid rgba(255,255,255,0.07);
                border-radius: 1rem;
                padding: 1rem;
                margin-bottom: 0.75rem;
                transition: all 0.2s;
            }
            .participant-card:hover {
                border-color: rgba(255,255,255,0.13);
                background: #1a1f2a;
            }
            .participant-header {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 0.75rem;
            }
            .participant-avatar {
                width: 44px;
                height: 44px;
                border-radius: 0.75rem;
                background: linear-gradient(135deg, #242d3d, #2e3a4e);
                border: 1px solid rgba(255,255,255,0.13);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                font-weight: bold;
                color: #60a5fa;
                flex-shrink: 0;
            }
            .participant-name {
                font-weight: 600;
                color: #f0f4f8;
                font-size: 0.95rem;
            }
            .participant-badge {
                margin-left: auto;
            }
            .participant-info {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                margin: 0.75rem 0;
                padding: 0.5rem 0;
                border-top: 1px solid rgba(255,255,255,0.07);
                border-bottom: 1px solid rgba(255,255,255,0.07);
            }
            .participant-info-item {
                display: flex;
                align-items: flex-start;
                gap: 0.6rem;
                font-size: 0.72rem;
                color: rgba(255,255,255,0.45);
                line-height: 1.4;
                word-break: break-all;
            }
            .participant-info-item i {
                width: 16px;
                margin-top: 2px;
                flex-shrink: 0;
            }
            .register-date {
                font-size: 0.65rem;
                color: rgba(255,255,255,0.25);
                margin-top: 0.5rem;
                text-align: right;
            }
        }
        
        @media (min-width: 769px) {
            .desktop-table {
                display: block;
            }
            .mobile-cards {
                display: none;
            }
        }
        
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

    {{-- Topbar dengan tombol kembali seperti sebelumnya --}}
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
                    <span class="hidden sm:inline">Semua Event</span>
                </a>
                <span class="text-white/22 hidden sm:inline">/</span>
                <span class="font-['DM_Sans'] text-base font-semibold text-[#f0f4f8]">Peserta</span>
            </div>
        </div>
        
        <span class="bg-blue-500/12 border border-blue-500/20 text-primary-400 text-[0.65rem] font-bold px-2.5 py-0.5 rounded-full tracking-wide whitespace-nowrap">ADMIN</span>
    </div>

    <div class="flex-1 p-4 sm:p-6 md:p-8">

        {{-- Event Info Card (responsive) --}}
        <div class="bg-[#0d1117] border border-white/7 rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-['DM_Serif_Display'] text-[1.1rem] sm:text-[1.25rem] text-[#f0f4f8] mb-1">{{ $event->name }}</h2>
                    <p class="text-[0.75rem] sm:text-[0.85rem] text-white/45 flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar-alt text-[11px]"></i>
                        {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y') }}
                    </p>
                    <p class="text-[0.7rem] sm:text-[0.8rem] text-white/30 mt-2 line-clamp-2">{{ Str::limit($event->description, 100) }}</p>
                </div>
                <div class="flex gap-6">
                    <div class="text-center">
                        <div class="font-['DM_Serif_Display'] text-[1.3rem] sm:text-[1.6rem] font-bold text-[#f0f4f8] leading-none">{{ $registrations->total() }}</div>
                        <div class="text-[0.6rem] sm:text-[0.7rem] text-white/30 uppercase tracking-wide mt-1">Pendaftar</div>
                    </div>
                    <div class="text-center">
                        <div class="font-['DM_Serif_Display'] text-[1.3rem] sm:text-[1.6rem] font-bold text-[#f0f4f8] leading-none">{{ $registrations->where('attended', true)->count() }}</div>
                        <div class="text-[0.6rem] sm:text-[0.7rem] text-white/30 uppercase tracking-wide mt-1">Hadir</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="mb-4 animate-fadeUp opacity-0" style="animation-delay: 0.08s">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/22 text-[13px]"></i>
                <input type="text" id="searchInput" oninput="filterTable()" 
                       placeholder="Cari nama, email, atau telepon..." 
                       class="w-full pl-9 pr-4 py-2.5 bg-[#1c2333] border border-white/7 rounded-xl text-[0.85rem] text-[#f0f4f8] placeholder-white/22 outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all">
            </div>
        </div>

        {{-- Participants Table (Desktop) --}}
        <div class="desktop-table bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden animate-fadeUp opacity-0" style="animation-delay: 0.1s">
            <div class="overflow-x-auto">
                <table class="admin-table w-full border-collapse min-w-200">
                    <thead>
                        <tr>
                            <th class="text-[0.65rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30 px-3 sm:px-4 py-2 sm:py-3">#</th>
                            <th class="text-[0.65rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30 px-3 sm:px-4 py-2 sm:py-3">Nama</th>
                            <th class="text-[0.65rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30 px-3 sm:px-4 py-2 sm:py-3">Email</th>
                            <th class="text-[0.65rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30 px-3 sm:px-4 py-2 sm:py-3">Telepon</th>
                            <th class="text-[0.65rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30 px-3 sm:px-4 py-2 sm:py-3">Tanggal Daftar</th>
                            <th class="text-[0.65rem] sm:text-[0.72rem] font-bold uppercase tracking-wide text-white/30 px-3 sm:px-4 py-2 sm:py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody id="participantsTableBodyDesktop">
                        @forelse($registrations as $index => $reg)
                        <tr class="hover:bg-white/2 transition-colors">
                            <td class="text-white/45 text-[0.75rem] sm:text-[0.8rem] px-3 sm:px-4 py-2 sm:py-3">{{ $registrations->firstItem() + $index }}</td>
                            <td class="px-3 sm:px-4 py-2 sm:py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-500/15 border border-blue-500/20 flex items-center justify-center text-[0.65rem] sm:text-[0.75rem] font-bold text-primary-400 shrink-0">
                                        {{ strtoupper(substr($reg->full_name ?? $reg->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="text-[0.8rem] sm:text-[0.875rem] font-semibold text-[#f0f4f8]">{{ $reg->full_name ?? ($reg->user->name ?? '-') }}</div>
                                </div>
                            </td>
                            <td class="text-white/45 text-[0.75rem] sm:text-[0.85rem] px-3 sm:px-4 py-2 sm:py-3">{{ $reg->email ?? ($reg->user->email ?? '-') }}</td>
                            <td class="text-white/45 text-[0.75rem] sm:text-[0.85rem] px-3 sm:px-4 py-2 sm:py-3">{{ $reg->phone ?? '-' }}</td>
                            <td class="text-white/45 text-[0.7rem] sm:text-[0.85rem] px-3 sm:px-4 py-2 sm:py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($reg->registration_date ?? $reg->created_at)->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-3 sm:px-4 py-2 sm:py-3">
                                @if($reg->attended)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] sm:text-[0.72rem] font-semibold bg-[#4ade80]/10 border border-[#4ade80]/20 text-[#4ade80] whitespace-nowrap">
                                        <i class="fa-solid fa-check-circle text-[8px] sm:text-[9px]"></i>
                                        Hadir
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] sm:text-[0.72rem] font-semibold bg-blue-500/10 border border-blue-500/20 text-primary-400 whitespace-nowrap">
                                        <i class="fa-regular fa-clock text-[8px] sm:text-[9px]"></i>
                                        Terdaftar
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 sm:py-16">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fa-regular fa-user text-3xl sm:text-4xl text-white/22"></i>
                                    <p class="text-white/45 text-[0.8rem] sm:text-[0.875rem]">Belum ada peserta yang mendaftar.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Card Layout --}}
        <div class="mobile-cards animate-fadeUp opacity-0" style="animation-delay: 0.1s">
            @forelse($registrations as $index => $reg)
            <div class="participant-card" data-name="{{ strtolower($reg->full_name ?? $reg->user->name ?? '') }}" 
                 data-email="{{ strtolower($reg->email ?? $reg->user->email ?? '') }}"
                 data-phone="{{ $reg->phone ?? '' }}">
                <div class="participant-header">
                    <div class="participant-avatar">
                        {{ strtoupper(substr($reg->full_name ?? $reg->user->name ?? 'U', 0, 2)) }}
                    </div>
                    <div>
                        <div class="participant-name">{{ $reg->full_name ?? ($reg->user->name ?? '-') }}</div>
                    </div>
                    <div class="participant-badge">
                        @if($reg->attended)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-[#4ade80]/10 border border-[#4ade80]/20 text-[#4ade80] whitespace-nowrap">
                                <i class="fa-solid fa-check-circle text-[9px]"></i>
                                Hadir
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-blue-500/10 border border-blue-500/20 text-primary-400 whitespace-nowrap">
                                <i class="fa-regular fa-clock text-[9px]"></i>
                                Terdaftar
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="participant-info">
                    <div class="participant-info-item">
                        <i class="fa-regular fa-envelope"></i>
                        <span>{{ $reg->email ?? ($reg->user->email ?? '-') }}</span>
                    </div>
                    <div class="participant-info-item">
                        <i class="fa-solid fa-phone"></i>
                        <span>{{ $reg->phone ?? '-' }}</span>
                    </div>
                </div>
                
                <div class="register-date">
                    <i class="fa-regular fa-calendar-alt mr-1 text-[9px]"></i>
                    {{ \Carbon\Carbon::parse($reg->registration_date ?? $reg->created_at)->translatedFormat('d M Y, H:i') }}
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="flex flex-col items-center gap-3">
                    <i class="fa-regular fa-user text-3xl text-white/22"></i>
                    <p class="text-white/45 text-sm">Belum ada peserta yang mendaftar.</p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($registrations->hasPages())
            <div class="mt-5 overflow-x-auto">
                {{ $registrations->links('pagination::tailwind') }}
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
    
    // Filter function for both desktop and mobile
    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        
        // Filter desktop table
        const desktopRows = document.querySelectorAll('#participantsTableBodyDesktop tr');
        desktopRows.forEach(row => {
            if (!row.cells || row.cells.length === 0) return;
            const name = row.cells[1]?.textContent.toLowerCase() ?? '';
            const email = row.cells[2]?.textContent.toLowerCase() ?? '';
            const phone = row.cells[3]?.textContent.toLowerCase() ?? '';
            
            const matches = name.includes(input) || email.includes(input) || phone.includes(input);
            row.style.display = matches ? '' : 'none';
        });
        
        // Filter mobile cards
        const mobileCards = document.querySelectorAll('.participant-card');
        mobileCards.forEach(card => {
            const name = card.dataset.name?.toLowerCase() ?? '';
            const email = card.dataset.email?.toLowerCase() ?? '';
            const phone = card.dataset.phone ?? '';
            
            const matches = name.includes(input) || email.includes(input) || phone.includes(input);
            card.style.display = matches ? '' : 'none';
        });
    }
</script>

</body>
</html>