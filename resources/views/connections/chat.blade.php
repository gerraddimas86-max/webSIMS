@extends('layouts.app')

@section('content')

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to   { opacity: 1; transform: scale(1); }
    }
    
    .animate-fadeUp {
        animation: fadeUp 0.4s ease forwards;
    }
    
    .animate-modalIn {
        animation: modalFadeIn 0.3s ease forwards;
    }
    
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
    
    .messages-area::-webkit-scrollbar { width: 4px; }
    .messages-area::-webkit-scrollbar-track { background: transparent; }
    .messages-area::-webkit-scrollbar-thumb { background: #242d3d; border-radius: 2px; }
    
    .msg-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        right: 0;
        background: #1c2333;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 0.6rem;
        overflow: hidden;
        z-index: 20;
        min-width: 100px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    }
    
    .msg-dropdown.open {
        display: block;
    }
    
    .edit-wrap {
        display: none;
        gap: 0.4rem;
        margin-top: 0.5rem;
        align-items: center;
    }
    
    .edit-wrap input {
        flex: 1;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 0.5rem;
        padding: 0.35rem 0.65rem;
        font-size: 0.8rem;
        color: #fff;
        outline: none;
    }
    
    .edit-wrap input:focus {
        border-color: rgba(255,255,255,0.4);
    }
    
    .msg-actions-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .msg-actions-wrap.sent {
        order: -1;
    }
    
    .btn-more {
        width: 28px;
        height: 28px;
        background: none;
        border: none;
        cursor: pointer;
        color: rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.4rem;
        transition: all 0.2s;
    }
    
    .btn-more:hover {
        color: #60a5fa;
        background: rgba(255,255,255,0.08);
    }
    
    .spinner-border {
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255,255,255,.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        display: inline-block;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    #toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }
    
    #toast {
        opacity: 0;
        transform: translateX(-50%) translateY(12px);
        pointer-events: none;
        transition: opacity 0.25s, transform 0.25s;
    }
</style>

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteModal" class="modal-backdrop">
    <div class="modal-container">
        <div class="text-center">
            <div class="w-14 h-14 rounded-full bg-[#f87171]/10 border border-[#f87171]/20 flex items-center justify-center mx-auto mb-4">
                <i class="fa-regular fa-trash-can text-2xl text-[#f87171]"></i>
            </div>
            <h3 class="font-['DM_Serif_Display'] text-xl text-[#f0f4f8] mb-2">Hapus Pesan?</h3>
            <p class="text-white/45 text-sm mb-5">Pesan yang dihapus tidak dapat dikembalikan. Apakah Anda yakin?</p>
            <div class="flex gap-3">
                <button id="cancelDeleteBtn" class="flex-1 px-4 py-2 rounded-xl bg-[#1c2333] border border-white/7 text-white/45 font-semibold text-sm hover:bg-[#242d3d] hover:text-[#f0f4f8] transition-all cursor-pointer">
                    Batal
                </button>
                <button id="confirmDeleteBtn" class="flex-1 px-4 py-2 rounded-xl bg-[#f87171] text-white font-semibold text-sm hover:bg-[#ef4444] hover:-translate-y-px transition-all cursor-pointer">
                    <i class="fa-regular fa-trash-can mr-1.5 text-[11px]"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<div class="w-full max-w-4xl mx-auto px-4 py-6 font-['DM_Sans'] text-[#f0f4f8]">

    <div class="mb-5 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
        <a href="{{ route('connections.index') }}" class="inline-flex items-center gap-2 text-white/45 hover:text-[#f0f4f8] transition-colors">
            <i class="fa-solid fa-arrow-left text-sm"></i>
            <span class="text-sm">Kembali ke Koneksi</span>
        </a>
    </div>

    <div class="bg-[#161b24] border border-white/7 rounded-2xl p-3.5 mb-3 flex items-center gap-3 animate-fadeUp opacity-0" style="animation-delay: 0.1s">
        <div class="w-11 h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-[0.85rem] font-bold text-primary-400">
            {{ strtoupper(substr($receiver->name, 0, 2)) }}
        </div>
        <div>
            <div class="text-[0.95rem] font-semibold text-[#f0f4f8]">{{ $receiver->name }}</div>
            <div class="flex items-center gap-1.5 text-[0.72rem] text-white/22">
                <div class="w-1.5 h-1.5 rounded-full bg-[#4ade80]"></div>
                Terhubung
            </div>
        </div>
    </div>

    <div class="bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden flex flex-col h-[calc(100vh-280px)] min-h-100 animate-fadeUp opacity-0" style="animation-delay: 0.15s">

        <div id="messagesArea" class="flex-1 overflow-y-auto p-4 flex flex-col gap-2.5 scroll-smooth">
            @forelse($messages as $msg)
                @if($msg->sender_id == Auth::id())
                    <div class="flex justify-end items-end gap-2 group" id="message-{{ $msg->id }}">
                        <div class="relative msg-actions-wrap sent">
                            <button class="btn-more" onclick="toggleDropdown({{ $msg->id }})">
                                <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                            </button>
                            <div class="msg-dropdown" id="dropdown-{{ $msg->id }}">
                                <button onclick="startEdit({{ $msg->id }})" class="flex items-center gap-2 w-full text-left px-3.5 py-2 text-[0.78rem] font-medium text-white/45 hover:bg-[#242d3d] hover:text-[#f0f4f8] transition-all">
                                    <i class="fa-regular fa-pen-to-square text-[11px]"></i>Edit
                                </button>
                                <button onclick="showDeleteModal({{ $msg->id }})" class="flex items-center gap-2 w-full text-left px-3.5 py-2 text-[0.78rem] font-medium text-white/45 hover:bg-[#242d3d] hover:text-[#f87171] transition-all">
                                    <i class="fa-regular fa-trash-can text-[11px]"></i>Hapus
                                </button>
                            </div>
                        </div>
                        <div class="bg-primary-600 rounded-2xl rounded-br-md px-3.5 py-2 max-w-[70%]">
                            <p class="text-white text-[0.875rem] leading-relaxed" id="text-{{ $msg->id }}">{{ $msg->message }}</p>
                            <div class="edit-wrap" id="edit-{{ $msg->id }}">
                                <input type="text" value="{{ $msg->message }}" id="edit-input-{{ $msg->id }}"
                                       class="bg-white/10 border border-white/18 rounded-lg px-2.5 py-1 text-[0.8rem] text-white outline-none focus:border-white/40"
                                       onkeydown="if(event.key==='Enter') saveEdit({{ $msg->id }}); if(event.key==='Escape') cancelEdit({{ $msg->id }});">
                                <button onclick="saveEdit({{ $msg->id }})" class="bg-white/20 border-none rounded-md px-3 py-1 text-[0.72rem] font-semibold text-white cursor-pointer hover:bg-white/30 transition-all">Simpan</button>
                                <button onclick="cancelEdit({{ $msg->id }})" class="bg-transparent border border-white/15 rounded-md px-3 py-1 text-[0.72rem] font-semibold text-white/55 cursor-pointer hover:text-white hover:bg-white/10 transition-all">Batal</button>
                            </div>
                            <span class="text-[0.65rem] text-white/45 block text-right mt-1">{{ $msg->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex justify-start">
                        <div class="bg-[#1c2333] border border-white/7 rounded-2xl rounded-bl-md px-3.5 py-2 max-w-[70%]">
                            <p class="text-white/80 text-[0.875rem] leading-relaxed">{{ $msg->message }}</p>
                            <span class="text-[0.65rem] text-white/22 block text-right mt-1">{{ $msg->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
                        </div>
                    </div>
                @endif
            @empty
                <div class="flex-1 flex flex-col items-center justify-center gap-2 py-8 text-center">
                    <i class="fa-regular fa-comment-dots text-3xl text-white/22"></i>
                    <p class="text-white/45 text-sm">Belum ada pesan</p>
                    <span class="text-white/18 text-xs">Mulai percakapan dengan {{ $receiver->name }}</span>
                </div>
            @endforelse
        </div>

        <div class="border-t border-white/7 p-4">
            <form id="chatForm" class="flex gap-2">
                @csrf
                <input type="text" id="messageInput" 
                       class="flex-1 bg-[#1c2333] border border-white/7 rounded-xl px-4 py-2.5 text-sm text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all"
                       placeholder="Tulis pesan..." autocomplete="off">
                <button type="submit" id="sendBtn"
                        class="w-11 h-11 bg-primary-600 rounded-xl flex items-center justify-center hover:bg-primary-500 hover:-translate-y-px transition-all duration-200">
                    <i class="fa-regular fa-paper-plane text-white text-sm"></i>
                </button>
            </form>
        </div>

    </div>

</div>

<script>
(function () {
    const receiverId = {{ $receiver->id }};
    const area = document.getElementById('messagesArea');
    const input = document.getElementById('messageInput');
    const form = document.getElementById('chatForm');
    const sendBtn = document.getElementById('sendBtn');
    
    let deleteMessageId = null;
    
    function scrollBottom() {
        area.scrollTop = area.scrollHeight;
    }
    scrollBottom();
    
    function csrf() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }
    
    function nowTime() {
        return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
    }
    
    function showToast(msg, type) {
        const toast = document.getElementById('toast') || createToast();
        const icon = type === 'success' ? 'fa-regular fa-circle-check' : 'fa-regular fa-circle-xmark';
        const color = type === 'success' ? '#4ade80' : '#f87171';
        const bg = type === 'success' ? 'rgba(74,222,128,.15)' : 'rgba(248,113,113,.12)';
        
        toast.style.background = bg;
        toast.style.border = '1px solid ' + (type === 'success' ? 'rgba(74,222,128,.25)' : 'rgba(248,113,113,.22)');
        toast.style.color = color;
        toast.innerHTML = '<i class="' + icon + ' text-[13px]"></i><span>' + msg + '</span>';
        toast.classList.add('show');
        
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
    
    function createToast() {
        const toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'fixed bottom-7 left-1/2 -translate-x-1/2 translate-y-3 flex items-center gap-2 py-2 px-5 rounded-full font-["DM_Sans"] text-[0.82rem] font-medium shadow-[0_8px_24px_rgba(0,0,0,0.4)] opacity-0 pointer-events-none transition-all duration-250 z-[9999] whitespace-nowrap';
        document.body.appendChild(toast);
        return toast;
    }
    
    // SHOW DELETE MODAL
    window.showDeleteModal = function(messageId) {
        deleteMessageId = messageId;
        const modal = document.getElementById('deleteModal');
        modal.classList.add('show');
    };
    
    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('show');
        deleteMessageId = null;
    }
    
    // CONFIRM DELETE - LANGSUNG HAPUS TANPA REFRESH
    async function confirmDelete() {
        if (!deleteMessageId) {
            hideDeleteModal();
            return;
        }
        
        const messageIdToDelete = deleteMessageId;
        const url = '/messages/' + messageIdToDelete;
        
        hideDeleteModal();
        
        try {
            const res = await fetch(url, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': csrf(), 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await res.json();
            
            if (data.success) {
                const row = document.getElementById('message-' + messageIdToDelete);
                if (row) {
                    row.style.transition = 'opacity 0.2s ease';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 200);
                }
                showToast('Pesan berhasil dihapus!', 'success');
            } else {
                showToast(data.message || 'Gagal menghapus pesan', 'error');
            }
        } catch (err) {
            console.error('Error:', err);
            showToast('Terjadi kesalahan', 'error');
        }
    }
    
    // TOGGLE DROPDOWN
    window.toggleDropdown = function(id) {
        const dd = document.getElementById('dropdown-' + id);
        document.querySelectorAll('.msg-dropdown').forEach(d => {
            if (d.id !== 'dropdown-' + id) d.classList.remove('open');
        });
        dd.classList.toggle('open');
    };
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.msg-actions-wrap')) {
            document.querySelectorAll('.msg-dropdown').forEach(d => d.classList.remove('open'));
        }
    });
    
    // EDIT MESSAGE
    window.startEdit = function(id) {
        document.getElementById('text-' + id).style.display = 'none';
        const wrap = document.getElementById('edit-' + id);
        wrap.style.display = 'flex';
        document.getElementById('edit-input-' + id).focus();
        document.getElementById('dropdown-' + id).classList.remove('open');
    };
    
    window.cancelEdit = function(id) {
        document.getElementById('text-' + id).style.display = '';
        document.getElementById('edit-' + id).style.display = 'none';
    };
    
    window.saveEdit = async function(id) {
        const newText = document.getElementById('edit-input-' + id).value.trim();
        if (!newText) return;
        
        const saveBtn = document.querySelector('#edit-' + id + ' button:first-of-type');
        const originalText = saveBtn.textContent;
        saveBtn.disabled = true;
        saveBtn.textContent = '...';
        
        try {
            const res = await fetch('/messages/' + id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: newText })
            });
            const data = await res.json();
            
            if (data.success) {
                document.getElementById('text-' + id).textContent = newText;
                document.getElementById('edit-input-' + id).value = newText;
                cancelEdit(id);
                showToast('Pesan berhasil diedit!', 'success');
            } else {
                showToast(data.message || 'Gagal mengedit pesan', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Terjadi kesalahan', 'error');
        } finally {
            saveBtn.disabled = false;
            saveBtn.textContent = originalText;
        }
    };
    
    // SEND MESSAGE
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const msg = input.value.trim();
        if (!msg) return;
        
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<div class="spinner-border"></div>';
        
        try {
            const res = await fetch('/connections/send/' + receiverId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: msg })
            });
            const data = await res.json();
            
            if (data.success) {
                const now = nowTime();
                const messageId = data.message.id;
                
                const row = document.createElement('div');
                row.className = 'flex justify-end items-end gap-2 group';
                row.id = 'message-' + messageId;
                row.innerHTML = `
                    <div class="relative msg-actions-wrap sent">
                        <button class="btn-more" onclick="toggleDropdown(${messageId})">
                            <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                        </button>
                        <div class="msg-dropdown" id="dropdown-${messageId}">
                            <button onclick="startEdit(${messageId})" class="flex items-center gap-2 w-full text-left px-3.5 py-2 text-[0.78rem] font-medium text-white/45 hover:bg-[#242d3d] hover:text-[#f0f4f8] transition-all">
                                <i class="fa-regular fa-pen-to-square text-[11px]"></i>Edit
                            </button>
                            <button onclick="showDeleteModal(${messageId})" class="flex items-center gap-2 w-full text-left px-3.5 py-2 text-[0.78rem] font-medium text-white/45 hover:bg-[#242d3d] hover:text-[#f87171] transition-all">
                                <i class="fa-regular fa-trash-can text-[11px]"></i>Hapus
                            </button>
                        </div>
                    </div>
                    <div class="bg-[#2563eb] rounded-2xl rounded-br-md px-3.5 py-2 max-w-[70%]">
                        <p class="text-white text-[0.875rem] leading-relaxed" id="text-${messageId}">${escapeHtml(msg)}</p>
                        <div class="edit-wrap" id="edit-${messageId}">
                            <input type="text" value="${escapeHtml(msg)}" id="edit-input-${messageId}"
                                   class="bg-white/10 border border-white/18 rounded-lg px-2.5 py-1 text-[0.8rem] text-white outline-none focus:border-white/40"
                                   onkeydown="if(event.key==='Enter') saveEdit(${messageId}); if(event.key==='Escape') cancelEdit(${messageId});">
                            <button onclick="saveEdit(${messageId})" class="bg-white/20 border-none rounded-md px-3 py-1 text-[0.72rem] font-semibold text-white cursor-pointer hover:bg-white/30 transition-all">Simpan</button>
                            <button onclick="cancelEdit(${messageId})" class="bg-transparent border border-white/15 rounded-md px-3 py-1 text-[0.72rem] font-semibold text-white/55 cursor-pointer hover:text-white hover:bg-white/10 transition-all">Batal</button>
                        </div>
                        <span class="text-[0.65rem] text-white/45 block text-right mt-1">${now}</span>
                    </div>
                `;
                area.appendChild(row);
                scrollBottom();
                input.value = '';
            }
        } catch (err) {
            console.error(err);
            showToast('Gagal mengirim pesan', 'error');
        } finally {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fa-regular fa-paper-plane text-white text-sm"></i>';
        }
    });
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
    
    document.getElementById('cancelDeleteBtn').addEventListener('click', hideDeleteModal);
    document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);
    
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) hideDeleteModal();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('deleteModal').classList.contains('show')) {
            hideDeleteModal();
        }
    });
})();
</script>

@endsection