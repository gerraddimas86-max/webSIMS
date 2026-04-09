<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Event - Admin | Community SIMS</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Animations only - Tailwind doesn't have these */
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
        
        /* Sidebar fixed positioning */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            z-index: 100;
        }
        
        /* Main content margin */
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
        
        /* Table styles */
        .admin-table th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            background: #0d1117;
        }
        
        .admin-table td {
            padding: 0.9rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            vertical-align: middle;
        }
        
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        
        .admin-table tr:hover td {
            background: rgba(255,255,255,0.02);
        }
        
        /* Pagination styling */
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
    </style>
</head>
<body class="font-['DM_Sans'] bg-[#080c12] text-[#f0f4f8] flex min-h-screen">

{{-- ── Modal Konfirmasi Hapus Event ─────────────────────────────────────── --}}
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

{{-- ── Sidebar ──────────────────────────────────────────────── --}}
<aside class="sidebar-fixed w-60 bg-[#0d1117] border-r border-white/7 flex flex-col">
    {{-- Sidebar glow --}}
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
           class="nav-item flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500/10 border-blue-500/18 text-primary-400' : '' }}">
            <i class="fa-solid fa-chart-simple text-[13px]"></i>
            Dashboard
            @if(request()->routeIs('admin.dashboard'))
                <i class="fa-solid fa-circle text-[5px] text-primary-400 ml-auto"></i>
            @endif
        </a>

        <a href="{{ route('admin.events.index') }}" 
           class="nav-item flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150 {{ request()->routeIs('admin.events.index') ? 'bg-blue-500/10 border-blue-500/18 text-primary-400' : '' }}">
            <i class="fa-solid fa-calendar-alt text-[13px]"></i>
            Kelola Event
            @if(request()->routeIs('admin.events.index'))
                <i class="fa-solid fa-circle text-[5px] text-primary-400 ml-auto"></i>
            @endif
        </a>

        <a href="{{ route('admin.events.create') }}" 
           class="nav-item flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-white/45 text-[0.85rem] font-medium border border-transparent hover:bg-white/5 hover:text-[#f0f4f8] transition-all duration-150 {{ request()->routeIs('admin.events.create') ? 'bg-blue-500/10 border-blue-500/18 text-primary-400' : '' }}">
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

    {{-- Topbar --}}
    <div class="sticky top-0 z-50 bg-[#0d1117]/85 backdrop-blur-xl border-b border-white/7 px-8 py-3.5 flex items-center justify-between">
        <div class="absolute -bottom-px left-[10%] right-[10%] h-px bg-linear-to-r from-transparent via-blue-500/25 to-transparent"></div>
        <span class="font-['DM_Sans'] text-base font-semibold text-[#f0f4f8]">Kelola Event</span>
        <span class="bg-blue-500/12 border border-blue-500/20 text-primary-400 text-[0.65rem] font-bold px-2.5 py-0.5 rounded-full tracking-wide">ADMIN</span>
    </div>

    <div class="flex-1 p-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
            <div>
                <h1 class="font-['DM_Serif_Display'] text-[1.6rem] text-[#f0f4f8] mb-1">Daftar Event</h1>
                <p class="text-[0.875rem] text-white/45">{{ $events->total() }} event terdaftar di sistem</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-xl font-['DM_Sans'] text-[0.875rem] font-semibold no-underline hover:bg-primary-500 hover:-translate-y-px transition-all duration-200 shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-plus text-[12px]"></i>
                Buat Event
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-5 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-xl px-5 py-3.5 text-[0.875rem] text-[#4ade80] flex items-center gap-2 animate-fadeUp opacity-0" style="animation-delay: 0.08s">
                <i class="fa-solid fa-circle-check text-[14px]"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Events Table --}}
        <div class="bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden animate-fadeUp opacity-0" style="animation-delay: 0.1s">
            <div class="overflow-x-auto">
                <table class="admin-table w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">#</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Nama Event</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Tanggal</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Pendaftar</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Status</th>
                            <th class="text-[0.72rem] font-bold uppercase tracking-[0.07em] text-white/30">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        <tr class="hover:bg-white/2 transition-colors">
                            <td class="text-white/45 text-[0.8rem]">{{ $loop->iteration }}</td>
                            <td>
                                <div class="font-semibold text-[#f0f4f8]">{{ $event->name }}</div>
                                <div class="text-[0.78rem] text-white/45 mt-0.5">{{ Str::limit($event->description, 50) }}</div>
                            </td>
                            <td class="text-white/45">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="font-semibold text-[#f0f4f8]">{{ $event->registrations_count ?? 0 }}</span>
                                <span class="text-white/45 text-[0.8rem]"> orang</span>
                            </td>
                            <td>
                                @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[0.72rem] font-semibold bg-blue-500/10 border border-blue-500/20 text-primary-400">
                                        <i class="fa-regular fa-clock text-[9px]"></i>
                                        Akan Datang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[0.72rem] font-semibold bg-white/5 border border-white/7 text-white/45">
                                        <i class="fa-regular fa-circle-check text-[9px]"></i>
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-2 flex-wrap">
                                    <a href="{{ route('admin.events.participants', $event->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.75rem] font-semibold no-underline bg-primary-600 text-white hover:bg-primary-500 transition-all">
                                        <i class="fa-solid fa-users text-[10px]"></i>
                                        Peserta
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.75rem] font-semibold no-underline bg-transparent border border-white/13 text-white/45 hover:bg-[#1c2333] hover:text-[#f0f4f8] hover:border-white/13 transition-all">
                                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                                        Edit
                                    </a>
                                    <button type="button" onclick="showDeleteModal({{ $event->id }}, '{{ addslashes($event->name) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.75rem] font-semibold no-underline bg-[#f87171]/10 border border-[#f87171]/20 text-[#f87171] hover:bg-[#f87171] hover:text-white hover:border-[#f87171] transition-all cursor-pointer">
                                        <i class="fa-regular fa-trash-can text-[10px]"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fa-regular fa-calendar-xmark text-4xl text-white/22"></i>
                                    <p class="text-white/45 text-[0.875rem]">Belum ada event yang dibuat.</p>
                                    <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-xl font-['DM_Sans'] text-[0.875rem] font-semibold no-underline hover:bg-primary-500 hover:-translate-y-px transition-all duration-200">
                                        <i class="fa-solid fa-plus text-[12px]"></i>
                                        Buat Event Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($events->hasPages())
            <div class="mt-5">
                {{ $events->links('pagination::tailwind') }}
            </div>
        @endif

    </div>
</div>

<script>
    // Variabel untuk menyimpan data event yang akan dihapus
    let deleteEventId = null;
    let deleteForm = null;
    
    function showDeleteModal(eventId, eventName) {
        deleteEventId = eventId;
        
        // Update nama event di modal
        document.getElementById('deleteEventName').innerHTML = '<strong class="text-[#f0f4f8]">"' + eventName + '"</strong>';
        
        // Buat form untuk delete
        deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = '/admin/events/' + eventId;
        deleteForm.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                               '<input type="hidden" name="_method" value="DELETE">';
        
        // Tampilkan modal
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');
    }
    
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
    
    // Event listeners
    document.getElementById('cancelDeleteBtn')?.addEventListener('click', hideModal);
    document.getElementById('confirmDeleteBtn')?.addEventListener('click', confirmDelete);
    
    // Tutup modal jika klik di luar area modal
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal();
        }
    });
    
    // Tutup modal dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('deleteModal');
            if (modal && modal.classList.contains('opacity-100')) {
                hideModal();
            }
        }
    });
</script>

</body>
</html>