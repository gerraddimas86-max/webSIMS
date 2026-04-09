<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            width: 240px;
            height: 100vh;
            z-index: 100;
        }
        
        .main-content {
            margin-left: 240px;
        }
        
        @media (max-width: 768px) {
            .sidebar-fixed {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        .admin-table th {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            background: #0d1117;
        }
        
        .admin-table td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        
        .admin-table tr:hover td {
            background: rgba(255,255,255,0.02);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.25rem;
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
        
        .pagination .disabled span {
            opacity: 0.3;
            cursor: not-allowed;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="font-['DM_Sans'] bg-[#080c12] text-[#f0f4f8] flex min-h-screen">

{{-- ── Sidebar ──────────────────────────────────────────────── --}}
<aside class="sidebar-fixed w-60 bg-[#0d1117] border-r border-white/7 flex flex-col">
    <div class="absolute -top-16 -left-16 w-64 h-64 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.08)_0%,transparent_70%)] pointer-events-none"></div>
    
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
           class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150">
            <i class="fa-solid fa-chart-simple text-[13px]"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.events.index') }}"
           class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150">
            <i class="fa-solid fa-calendar-alt text-[13px]"></i>
            Kelola Event
        </a>

        <a href="{{ route('admin.events.create') }}"
           class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150">
            <i class="fa-solid fa-plus-circle text-[13px]"></i>
            Buat Event
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

    {{-- Topbar --}}
    <div class="sticky top-0 z-50 bg-[#0d1117]/85 backdrop-blur-xl border-b border-white/7 px-8 py-3.5 flex items-center justify-between">
        <div class="absolute -bottom-px left-[10%] right-[10%] h-px bg-linear-to-r from-transparent via-blue-500/25 to-transparent"></div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.events.index') }}" class="flex items-center gap-1.5 text-[0.82rem] text-white/45 no-underline hover:text-[#f0f4f8] transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                Semua Event
            </a>
            <span class="text-white/22">/</span>
            <span class="font-['DM_Sans'] text-base font-semibold text-[#f0f4f8]">Peserta</span>
        </div>
        <span class="bg-blue-500/12 border border-blue-500/20 text-primary-400 text-[0.65rem] font-bold px-2.5 py-0.5 rounded-full tracking-wide">ADMIN</span>
    </div>

    <div class="flex-1 p-8">

        {{-- Event Info Card --}}
        <div class="bg-[#0d1117] rounded-2xl p-6 mb-8 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-['DM_Serif_Display'] text-[1.25rem] text-[#f0f4f8] mb-1">{{ $event->name }}</h2>
                    <p class="text-[0.85rem] text-white/45 flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar-alt text-[11px]"></i>
                        {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y') }}
                    </p>
                    <p class="text-[0.8rem] text-white/30 mt-2 line-clamp-2">{{ Str::limit($event->description, 100) }}</p>
                </div>
                <div class="flex gap-6">
                    <div class="text-center">
                        <div class="font-['DM_Serif_Display'] text-[1.6rem] font-bold text-[#f0f4f8] leading-none">{{ $registrations->total() }}</div>
                        <div class="text-[0.7rem] text-white/30 uppercase tracking-wide mt-1">Pendaftar</div>
                    </div>
                    <div class="text-center">
                        <div class="font-['DM_Serif_Display'] text-[1.6rem] font-bold text-[#f0f4f8] leading-none">{{ $registrations->where('attended', true)->count() }}</div>
                        <div class="text-[0.7rem] text-white/30 uppercase tracking-wide mt-1">Hadir</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Participants Table --}}
        <div class="bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden animate-fadeUp opacity-0" style="animation-delay: 0.1s">
            <div class="px-6 py-4 border-b border-white/7 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span class="font-['DM_Sans'] text-[0.85rem] font-semibold text-[#f0f4f8]">
                    <i class="fa-solid fa-users-list text-[12px] mr-2 text-white/45"></i>
                    Daftar Peserta
                </span>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/22 text-[12px]"></i>
                    <input type="text" id="searchInput" oninput="filterTable()" 
                           placeholder="Cari nama, email, telepon, atau fakultas..." 
                           class="pl-9 pr-4 py-2 bg-[#1c2333] border border-white/7 rounded-xl text-[0.85rem] text-[#f0f4f8] placeholder-white/22 outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all w-full sm:w-80">
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="admin-table w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">#</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Nama Lengkap</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Email</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">No. Telepon</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Fakultas</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Tanggal Daftar</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Status</th>
                        </tr>
                    </thead>
                    <tbody id="participantsTableBody">
                        @forelse($registrations as $index => $reg)
                        <tr class="hover:bg-white/2 transition-colors">
                            <td class="text-white/45 text-[0.8rem]">{{ $registrations->firstItem() + $index }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-500/15 border border-blue-500/20 flex items-center justify-center text-[0.75rem] font-bold text-primary-400 shrink-0">
                                        {{ strtoupper(substr($reg->full_name ?? $reg->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="text-[0.875rem] font-semibold text-[#f0f4f8]">{{ $reg->full_name ?? $reg->user->name }}</div>
                                </div>
                            </td>
                            <td class="text-white/45 text-[0.85rem]">{{ $reg->email ?? $reg->user->email ?? '-' }}</td>
                            <td class="text-white/45 text-[0.85rem]">{{ $reg->phone ?? '-' }}</td>
                            <td class="text-white/45 text-[0.85rem]">{{ $reg->faculty ?? '-' }}</td>
                            <td class="text-white/45 text-[0.85rem]">{{ \Carbon\Carbon::parse($reg->registration_date ?? $reg->created_at)->translatedFormat('d M Y, H:i') }}</td>
                            <td>
                                @if($reg->attended)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[0.72rem] font-semibold bg-[#4ade80]/10 border border-[#4ade80]/20 text-[#4ade80]">
                                        <i class="fa-solid fa-check-circle text-[9px]"></i>
                                        Hadir
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[0.72rem] font-semibold bg-blue-500/10 border border-blue-500/20 text-primary-400">
                                        <i class="fa-regular fa-clock text-[9px]"></i>
                                        Terdaftar
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fa-regular fa-user text-4xl text-white/22"></i>
                                    <p class="text-white/45 text-[0.875rem]">Belum ada peserta yang mendaftar.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($registrations->hasPages())
            <div class="mt-5">
                {{ $registrations->links('pagination::tailwind') }}
            </div>
        @endif

    </div>
</div>

<script>
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#participantsTableBody tr');
    
    rows.forEach(row => {
        // Skip jika row kosong atau tidak memiliki cells
        if (!row.cells || row.cells.length === 0) return;
        
        // Ambil teks dari kolom yang ingin dicari
        const name = row.cells[1]?.textContent.toLowerCase() ?? '';
        const email = row.cells[2]?.textContent.toLowerCase() ?? '';
        const phone = row.cells[3]?.textContent.toLowerCase() ?? '';
        const institution = row.cells[4]?.textContent.toLowerCase() ?? '';
        
        // Cek apakah kata kunci cocok dengan salah satu kolom
        const matches = name.includes(input) || 
                       email.includes(input) || 
                       phone.includes(input) || 
                       institution.includes(input);
        
        // Tampilkan atau sembunyikan row
        row.style.display = matches ? '' : 'none';
    });
}

// Optional: Reset filter function
function resetFilter() {
    document.getElementById('searchInput').value = '';
    filterTable();
}
</script>

</body>
</html>