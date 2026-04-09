@extends('layouts.app')

@section('content')

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    .animate-fadeUp {
        animation: fadeUp 0.5s ease forwards;
    }
    
    .animate-modalIn {
        animation: modalFadeIn 0.3s ease forwards;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .spinner {
        width: 13px; height: 13px;
        border: 2px solid rgba(96,165,250,.3);
        border-top-color: #60a5fa;
        border-radius: 50%;
        animation: spin .6s linear infinite;
        flex-shrink: 0;
    }
    
    /* Modal backdrop */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(8, 12, 18, 0.85);
        backdrop-filter: blur(8px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    
    .modal-backdrop.show {
        opacity: 1;
        visibility: visible;
    }
    
    .modal-container {
        background: #161b24;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 1.5rem;
        max-width: 400px;
        width: 90%;
        padding: 1.5rem;
        animation: modalFadeIn 0.3s ease forwards;
    }
</style>

{{-- Toast --}}
<div id="toast" class="fixed bottom-7 left-1/2 -translate-x-1/2 translate-y-3 flex items-center gap-2 py-2 px-5 rounded-full font-['DM_Sans'] text-[0.82rem] font-medium shadow-[0_8px_24px_rgba(0,0,0,0.4)] opacity-0 pointer-events-none transition-all duration-250 z-9999 whitespace-nowrap"></div>

{{-- Modal Konfirmasi Putus Koneksi --}}
<div id="disconnectModal" class="modal-backdrop">
    <div class="modal-container">
        <div class="text-center">
            <div class="w-14 h-14 rounded-full bg-[#f87171]/10 border border-[#f87171]/20 flex items-center justify-center mx-auto mb-4">
                <i class="fa-regular fa-circle-xmark text-2xl text-[#f87171]"></i>
            </div>
            <h3 class="font-['DM_Serif_Display'] text-xl text-[#f0f4f8] mb-2">Putuskan Koneksi?</h3>
            <p class="text-white/45 text-sm mb-5">Anda tidak akan bisa chat dengan user ini sampai terhubung kembali. Apakah Anda yakin?</p>
            <div class="flex gap-3">
                <button id="cancelDisconnectBtn" class="flex-1 px-4 py-2 rounded-xl bg-[#1c2333] border border-white/7 text-white/45 font-semibold text-sm hover:bg-[#242d3d] hover:text-[#f0f4f8] transition-all cursor-pointer">
                    Batal
                </button>
                <button id="confirmDisconnectBtn" class="flex-1 px-4 py-2 rounded-xl bg-[#f87171] text-white font-semibold text-sm hover:bg-[#ef4444] hover:-translate-y-px transition-all cursor-pointer">
                    <i class="fa-regular fa-circle-xmark mr-1.5 text-[11px]"></i>
                    Putuskan
                </button>
            </div>
        </div>
    </div>
</div>

<div class="w-full max-w-6xl mx-auto pb-16 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- Header --}}
    <div class="mb-7 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
        <h1 class="font-['DM_Serif_Display'] text-[1.65rem] text-[#f0f4f8] mb-1">Koneksi</h1>
        <p class="text-[0.875rem] text-white/45">Temukan dan terhubung dengan anggota komunitas lainnya</p>
    </div>

    {{-- Search & filter --}}
    <div class="bg-[#161b24] border border-white/7 rounded-2xl p-3.5 mb-5 flex flex-col sm:flex-row gap-3 animate-fadeUp opacity-0" style="animation-delay: 0.1s">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/22 text-[13px]"></i>
            <input type="text" id="searchInput" 
                   class="w-full bg-[#1c2333] border border-white/7 rounded-xl py-2 pl-9 pr-4 font-['DM_Sans'] text-[0.875rem] text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all"
                   placeholder="Cari nama atau email…">
        </div>
        <div class="flex gap-2">
            <select id="filterRole" class="bg-[#1c2333] border border-white/7 rounded-xl py-2 px-3.5 font-['DM_Sans'] text-[0.85rem] text-white/45 outline-none cursor-pointer focus:border-primary-500 transition-all">
                <option value="all">Semua Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button id="resetFilter" class="w-9.5 h-9.5 bg-[#1c2333] border border-white/7 rounded-xl flex items-center justify-center cursor-pointer text-white/22 hover:text-[#f0f4f8] hover:border-white/13 hover:bg-[#242d3d] transition-all" title="Reset filter">
                <i class="fa-solid fa-arrow-rotate-left text-[13px]"></i>
            </button>
        </div>
    </div>

    {{-- Tab bar --}}
    <div class="flex gap-1 border-b border-white/7 mb-5 animate-fadeUp opacity-0" style="animation-delay: 0.15s">
        <button class="tab-btn active px-4 py-2.5 text-[0.85rem] font-medium text-white/45 bg-none border-b-2 border-transparent cursor-pointer font-['DM_Sans'] hover:text-[#f0f4f8] transition-all flex items-center gap-1.5 -mb-px" data-tab="all">
            <i class="fa-solid fa-users text-[12px]"></i>
            Semua User
        </button>
        <button class="tab-btn px-4 py-2.5 text-[0.85rem] font-medium text-white/45 bg-none border-b-2 border-transparent cursor-pointer font-['DM_Sans'] hover:text-[#f0f4f8] transition-all flex items-center gap-1.5 -mb-px" data-tab="requests">
            <i class="fa-regular fa-paper-plane text-[12px]"></i>
            Request Masuk
            @if($requests->count() > 0)
                <span class="bg-[#f87171]/15 border border-[#f87171]/25 text-[#f87171] text-[0.65rem] font-bold px-1.5 py-0.5 rounded-full">{{ $requests->count() }}</span>
            @endif
        </button>
        <button class="tab-btn px-4 py-2.5 text-[0.85rem] font-medium text-white/45 bg-none border-b-2 border-transparent cursor-pointer font-['DM_Sans'] hover:text-[#f0f4f8] transition-all flex items-center gap-1.5 -mb-px" data-tab="connected">
            <i class="fa-regular fa-circle-check text-[12px]"></i>
            Terhubung
            <span class="bg-[#242d3d] text-white/22 text-[0.65rem] font-bold px-1.5 py-0.5 rounded-full">{{ $connections->count() }}</span>
        </button>
    </div>

    {{-- ── Tab: Semua User ──────────────────────────────────── --}}
    <div id="tab-all" class="tab-content">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5" id="allUsersGrid">
            @forelse($users as $user)
                @php $status = $user->connection_status ?? 'not_connected'; @endphp
                <div class="bg-[#161b24] border border-white/7 rounded-2xl p-4.5 hover:border-white/13 hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(0,0,0,0.3)] transition-all duration-200"
                     data-name="{{ strtolower($user->name) }}"
                     data-email="{{ strtolower($user->email) }}"
                     data-role="{{ $user->role ?? 'user' }}">

                    <div class="flex items-start justify-between gap-2 mb-3.5">
                        <div class="flex items-center gap-2.5 flex-1 min-w-0">
                            {{-- Avatar bisa diklik ke profil --}}
                            <a href="{{ route('profile.show', $user->id) }}" class="w-11 h-11 rounded-xl bg-blue-500/15 border border-blue-500/22 flex items-center justify-center text-[0.85rem] font-bold text-primary-400 shrink-0 hover:scale-105 hover:border-blue-500/40 transition-all">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </a>
                            {{-- Nama dan email bisa diklik ke profil --}}
                            <a href="{{ route('profile.show', $user->id) }}" class="min-w-0 hover:text-primary-400 transition-all">
                                <div class="text-[0.875rem] font-semibold text-[#f0f4f8] truncate">{{ $user->name }}</div>
                                <div class="text-[0.75rem] text-white/22 truncate">{{ $user->email }}</div>
                            </a>
                        </div>
                        <span class="text-[0.65rem] font-semibold px-2 py-0.5 rounded-full bg-[#242d3d] border border-white/7 text-white/22 whitespace-nowrap {{ ($user->role ?? 'user') === 'admin' ? 'bg-blue-500/10 border-blue-500/20 text-primary-400' : '' }}">
                            {{ ucfirst($user->role ?? 'User') }}
                        </span>
                    </div>

                    <div class="text-[0.82rem] text-white/45 leading-relaxed mb-3.5 line-clamp-2">{{ $user->bio ?? 'Belum ada bio' }}</div>

                    <div class="flex items-center justify-between pt-3 border-t border-white/7 gap-2">
                        <div class="flex items-center gap-1.5 text-[0.72rem] text-white/22">
                            <i class="fa-regular fa-calendar-alt text-[10px]"></i>
                            {{ $user->created_at->diffForHumans() }}
                        </div>

                        <div class="flex gap-1.5">
                            @if($status === 'accepted')
                                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.78rem] font-semibold bg-[#4ade80]/8 border border-[#4ade80]/20 text-[#4ade80] cursor-default">
                                    <i class="fa-regular fa-circle-check text-[11px]"></i>
                                    Terhubung
                                </button>
                                <a href="{{ route('connections.chat', $user->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.78rem] font-semibold bg-[#1c2333] border border-white/13 text-white/45 hover:bg-[#242d3d] hover:text-[#f0f4f8] transition-all">
                                    <i class="fa-regular fa-comment text-[11px]"></i>
                                    Chat
                                </a>
                            @elseif($status === 'pending')
                                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.78rem] font-semibold bg-[#fbbf24]/8 border border-[#fbbf24]/20 text-[#fbbf24] cursor-default">
                                    <i class="fa-regular fa-clock text-[11px]"></i>
                                    Menunggu
                                </button>
                            @else
                                <button class="btn-connect inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.78rem] font-semibold bg-blue-500/8 border border-blue-500/20 text-primary-400 hover:bg-blue-500/18 hover:border-blue-500/35 hover:-translate-y-px transition-all cursor-pointer"
                                        onclick="sendRequest({{ $user->id }}, this)">
                                    <i class="fa-solid fa-user-plus text-[11px]"></i>
                                    Connect
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-14">
                    <div class="flex flex-col items-center gap-3">
                        <i class="fa-regular fa-user text-4xl text-white/22"></i>
                        <p class="text-white/45 text-[0.875rem]">Tidak ada user lain ditemukan</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ── Tab: Request Masuk ───────────────────────────────── --}}
    <div id="tab-requests" class="tab-content hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
            @forelse($requests as $req)
                <div class="bg-[#161b24] border border-[#fbbf24]/12 rounded-2xl p-4.5" id="req-card-{{ $req->requester_id }}">

                    <div class="flex items-start justify-between gap-2 mb-3.5">
                        <div class="flex items-center gap-2.5 flex-1 min-w-0">
                            {{-- Avatar bisa diklik ke profil --}}
                            <a href="{{ route('profile.show', $req->requester->id) }}" class="w-11 h-11 rounded-xl bg-[#fbbf24]/12 border border-[#fbbf24]/20 flex items-center justify-center text-[0.85rem] font-bold text-[#fbbf24] shrink-0 hover:scale-105 hover:border-[#fbbf24]/40 transition-all">
                                {{ strtoupper(substr($req->requester->name, 0, 2)) }}
                            </a>
                            {{-- Nama dan email bisa diklik ke profil --}}
                            <a href="{{ route('profile.show', $req->requester->id) }}" class="min-w-0 hover:text-[#fbbf24] transition-all">
                                <div class="text-[0.875rem] font-semibold text-[#f0f4f8] truncate">{{ $req->requester->name }}</div>
                                <div class="text-[0.75rem] text-white/22 truncate">{{ $req->requester->email }}</div>
                            </a>
                        </div>
                        <span class="text-[0.65rem] font-semibold px-2 py-0.5 rounded-full bg-[#fbbf24]/10 border border-[#fbbf24]/20 text-[#fbbf24] whitespace-nowrap">Menunggu</span>
                    </div>

                    <div class="text-[0.82rem] text-white/45 leading-relaxed mb-3.5 line-clamp-2">{{ $req->requester->bio ?? 'Ingin terhubung dengan Anda' }}</div>

                    <div class="flex gap-2 pt-3 border-t border-white/7">
                        <button class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.78rem] font-semibold bg-[#4ade80]/8 border border-[#4ade80]/20 text-[#4ade80] hover:bg-[#4ade80]/18 hover:border-[#4ade80]/35 hover:-translate-y-px transition-all cursor-pointer"
                                onclick="acceptRequest({{ $req->requester_id }}, this)">
                            <i class="fa-regular fa-circle-check text-[11px]"></i>
                            Terima
                        </button>
                        <button class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.78rem] font-semibold bg-[#f87171]/7 border border-[#f87171]/18 text-[#f87171] hover:bg-[#f87171]/16 hover:border-[#f87171]/30 hover:-translate-y-px transition-all cursor-pointer"
                                onclick="rejectRequest({{ $req->requester_id }}, this)">
                            <i class="fa-regular fa-circle-xmark text-[11px]"></i>
                            Tolak
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-14">
                    <div class="flex flex-col items-center gap-3">
                        <i class="fa-regular fa-envelope text-4xl text-white/22"></i>
                        <p class="text-white/45 text-[0.875rem]">Tidak ada request masuk</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ── Tab: Terhubung ───────────────────────────────────── --}}
    <div id="tab-connected" class="tab-content hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
            @forelse($connections as $connection)
                @php
                    $peer = $connection->requester_id == Auth::id()
                          ? $connection->receiver
                          : $connection->requester;
                @endphp
                <div class="bg-[#161b24] border border-[#4ade80]/12 rounded-2xl p-4.5" id="conn-card-{{ $peer->id }}">

                    <div class="flex items-start justify-between gap-2 mb-3.5">
                        <div class="flex items-center gap-2.5 flex-1 min-w-0">
                            {{-- Avatar bisa diklik ke profil --}}
                            <a href="{{ route('profile.show', $peer->id) }}" class="w-11 h-11 rounded-xl bg-[#4ade80]/12 border border-[#4ade80]/20 flex items-center justify-center text-[0.85rem] font-bold text-[#4ade80] shrink-0 hover:scale-105 hover:border-[#4ade80]/40 transition-all">
                                {{ strtoupper(substr($peer->name, 0, 2)) }}
                            </a>
                            {{-- Nama dan email bisa diklik ke profil --}}
                            <a href="{{ route('profile.show', $peer->id) }}" class="min-w-0 hover:text-[#4ade80] transition-all">
                                <div class="text-[0.875rem] font-semibold text-[#f0f4f8] truncate">{{ $peer->name }}</div>
                                <div class="text-[0.75rem] text-white/22 truncate">{{ $peer->email }}</div>
                            </a>
                        </div>
                        <span class="text-[0.65rem] font-semibold px-2 py-0.5 rounded-full bg-[#4ade80]/8 border border-[#4ade80]/18 text-[#4ade80] whitespace-nowrap">Terhubung</span>
                    </div>

                    <div class="flex gap-2 pt-3 border-t border-white/7">
                        <a href="{{ route('connections.chat', $peer->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.78rem] font-semibold bg-blue-500/8 border border-blue-500/20 text-primary-400 hover:bg-blue-500/18 hover:border-blue-500/35 hover:-translate-y-px transition-all">
                            <i class="fa-regular fa-comment text-[11px]"></i>
                            Chat
                        </a>
                        <button class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.78rem] font-semibold bg-[#f87171]/7 border border-[#f87171]/18 text-[#f87171] hover:bg-[#f87171]/16 hover:border-[#f87171]/30 hover:-translate-y-px transition-all cursor-pointer"
                                onclick="showDisconnectModal({{ $peer->id }}, this)">
                            <i class="fa-regular fa-circle-xmark text-[11px]"></i>
                            Putuskan
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-14">
                    <div class="flex flex-col items-center gap-3">
                        <i class="fa-regular fa-users text-4xl text-white/22"></i>
                        <p class="text-white/45 text-[0.875rem]">Belum ada koneksi</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>

<script>
(function () {
    /* ── Tab switching ──────────────────────────────────────── */
    document.querySelectorAll('.tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tab = this.dataset.tab;

            document.querySelectorAll('.tab-btn').forEach(function (b) {
                b.classList.remove('active');
                b.style.color = 'rgba(255,255,255,0.45)';
                b.style.borderBottomColor = 'transparent';
            });
            this.classList.add('active');
            this.style.color = '#60a5fa';
            this.style.borderBottomColor = '#60a5fa';

            document.querySelectorAll('.tab-content').forEach(function (c) {
                c.classList.add('hidden');
            });
            document.getElementById('tab-' + tab).classList.remove('hidden');
        });
    });

    /* ── Search & filter (only affects "all" tab) ───────────── */
    var searchInput = document.getElementById('searchInput');
    var filterRole  = document.getElementById('filterRole');
    var resetBtn    = document.getElementById('resetFilter');

    function applyFilter() {
        var q    = searchInput.value.toLowerCase().trim();
        var role = filterRole.value;

        document.querySelectorAll('#allUsersGrid > div').forEach(function (card) {
            var nameMatch  = card.dataset.name?.includes(q);
            var emailMatch = card.dataset.email?.includes(q);
            var roleMatch  = role === 'all' || card.dataset.role === role;
            card.style.display = (nameMatch || emailMatch) && roleMatch ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', applyFilter);
    filterRole.addEventListener('change', applyFilter);
    resetBtn.addEventListener('click', function () {
        searchInput.value = '';
        filterRole.value  = 'all';
        applyFilter();
    });

    /* ── Toast ──────────────────────────────────────────────── */
    var toastEl    = document.getElementById('toast');
    var toastTimer = null;

    function showToast(msg, type) {
        var icon = type === 'success' ? 'fa-regular fa-circle-check' :
                   type === 'error' ? 'fa-regular fa-circle-xmark' : 'fa-regular fa-circle-info';
        var color = type === 'success' ? '#4ade80' : (type === 'error' ? '#f87171' : '#60a5fa');
        var bg = type === 'success' ? 'rgba(74,222,128,.15)' : (type === 'error' ? 'rgba(248,113,113,.12)' : 'rgba(59,130,246,.12)');
        
        toastEl.style.background = bg;
        toastEl.style.border = '1px solid ' + (type === 'success' ? 'rgba(74,222,128,.25)' : (type === 'error' ? 'rgba(248,113,113,.22)' : 'rgba(59,130,246,.22)'));
        toastEl.style.color = color;
        toastEl.innerHTML = '<i class="' + icon + ' text-[13px]"></i><span>' + msg + '</span>';

        toastEl.classList.remove('opacity-0', 'pointer-events-none');
        toastEl.classList.add('opacity-100');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () {
            toastEl.classList.remove('opacity-100');
            toastEl.classList.add('opacity-0', 'pointer-events-none');
        }, 3000);
    }

    /* ── CSRF token ─────────────────────────────────────────── */
    function csrf() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    /* ── Send connection request ────────────────────────────── */
    window.sendRequest = async function (userId, btn) {
        btn.disabled = true;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<div class="spinner"></div>';
        
        try {
            const response = await fetch('/connections/request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ user_id: userId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                btn.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[0.78rem] font-semibold bg-[#fbbf24]/8 border border-[#fbbf24]/20 text-[#fbbf24] cursor-default';
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-regular fa-clock text-[11px]"></i><span>Menunggu</span>';
                showToast(data.message || 'Request koneksi berhasil dikirim!', 'success');
            } else {
                showToast(data.message || 'Gagal mengirim request', 'error');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    };

    /* ── Accept request ─────────────────────────────────────── */
    window.acceptRequest = async function (userId, btn) {
        btn.disabled = true;
        btn.innerHTML = '<div class="spinner"></div><span>Menerima…</span>';

        try {
            var res  = await fetch('/connections/accept-request/' + userId, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrf(), 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            var data = await res.json();

            if (data.success) {
                showToast('Koneksi berhasil diterima!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Gagal menerima request', 'error');
                btn.disabled = false;
            }
        } catch (e) {
            console.error(e);
            showToast('Terjadi kesalahan', 'error');
            btn.disabled = false;
        }
    };

    /* ── Reject request ─────────────────────────────────────── */
    window.rejectRequest = async function (userId, btn) {
        btn.disabled = true;
        btn.innerHTML = '<div class="spinner"></div><span>Menolak…</span>';

        try {
            var res  = await fetch('/connections/reject-request/' + userId, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrf(), 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            var data = await res.json();

            if (data.success) {
                showToast('Request ditolak', 'info');
                var card = document.getElementById('req-card-' + userId);
                if (card) {
                    card.style.transition = 'opacity .4s, transform .4s';
                    card.style.opacity    = '0';
                    card.style.transform  = 'scale(.95)';
                    setTimeout(function () { card.remove(); }, 400);
                }
            } else {
                showToast(data.message || 'Gagal menolak request', 'error');
                btn.disabled = false;
            }
        } catch (e) {
            console.error(e);
            showToast('Terjadi kesalahan', 'error');
            btn.disabled = false;
        }
    };

    /* ── Disconnect modal ───────────────────────────────────── */
    let disconnectUserId = null;
    let disconnectBtn = null;

    window.showDisconnectModal = function (userId, btn) {
        disconnectUserId = userId;
        disconnectBtn = btn;
        const modal = document.getElementById('disconnectModal');
        modal.classList.add('show');
    };

    function hideDisconnectModal() {
        const modal = document.getElementById('disconnectModal');
        modal.classList.remove('show');
    }

    async function confirmDisconnect() {
        if (!disconnectUserId) {
            hideDisconnectModal();
            return;
        }
        
        const btn = disconnectBtn;
        if (!btn) {
            hideDisconnectModal();
            return;
        }
        
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<div class="spinner"></div><span>Memutus…</span>';
        
        hideDisconnectModal();

        try {
            const response = await fetch('/connections/remove-connection/' + disconnectUserId, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': csrf(), 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();

            if (data.success) {
                showToast('Koneksi berhasil diputuskan!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Gagal memutuskan koneksi', 'error');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        } finally {
            disconnectUserId = null;
            disconnectBtn = null;
        }
    }

    // Event listeners untuk modal
    document.getElementById('cancelDisconnectBtn').addEventListener('click', hideDisconnectModal);
    document.getElementById('confirmDisconnectBtn').addEventListener('click', confirmDisconnect);

    document.getElementById('disconnectModal').addEventListener('click', function(e) {
        if (e.target === this) hideDisconnectModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('disconnectModal').classList.contains('show')) {
            hideDisconnectModal();
        }
    });

    /* Set active tab style on load */
    document.querySelector('.tab-btn.active').click();
})();
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

@endsection