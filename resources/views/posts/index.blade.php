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
    
    @keyframes likePop {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }
    
    .animate-fadeUp {
        animation: fadeUp 0.5s ease both;
    }
    
    .animate-modalIn {
        animation: modalFadeIn 0.3s ease forwards;
    }
    
    .post-card:nth-child(1) { animation-delay: 0.04s; }
    .post-card:nth-child(2) { animation-delay: 0.08s; }
    .post-card:nth-child(3) { animation-delay: 0.12s; }
    .post-card:nth-child(4) { animation-delay: 0.16s; }
    .post-card:nth-child(5) { animation-delay: 0.20s; }
    
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
    
    .comment-item .edit-comment-form {
        display: none;
    }
    
    .comment-item.edit-mode .comment-text {
        display: none;
    }
    
    .comment-item.edit-mode .edit-comment-form {
        display: block;
    }
    
    /* Animasi untuk tombol like */
    .like-btn i {
        transition: transform 0.2s ease;
    }
    
    .like-btn:active i {
        transform: scale(1.2);
    }
    
    .like-btn.liked-animation i {
        animation: likePop 0.3s ease;
    }
</style>

{{-- Toast Notification --}}
<div id="toastNotification" class="fixed bottom-8 left-1/2 -translate-x-1/2 translate-y-4 bg-[#1c2333] border border-white/10 rounded-full py-3 px-6 flex items-center gap-3 font-['DM_Sans'] text-sm font-medium text-[#f0f4f8] shadow-[0_8px_24px_rgba(0,0,0,0.4)] z-1100 opacity-0 invisible transition-all duration-300">
    <i id="toastIcon" class="fa-regular fa-circle-check text-sm"></i>
    <span id="toastMessage"></span>
</div>

{{-- Modal Konfirmasi Hapus Postingan --}}
<div id="deleteModal" class="fixed inset-0 bg-[#080c12]/80 backdrop-blur-md z-1000 flex items-center justify-center opacity-0 invisible transition-all duration-300">
    <div class="bg-[#161b24] border border-white/7 rounded-2xl max-w-100 w-[90%] p-6 animate-modalIn">
        <div class="text-center">
            <div class="w-14 h-14 rounded-full bg-[#f87171]/10 border border-[#f87171]/20 flex items-center justify-center mx-auto mb-4">
                <i class="fa-regular fa-trash-can text-2xl text-[#f87171]"></i>
            </div>
            <h3 class="font-['DM_Serif_Display'] text-xl text-[#f0f4f8] mb-2">Hapus Postingan?</h3>
            <p class="text-white/45 text-sm mb-5">Postingan yang dihapus tidak dapat dikembalikan. Apakah Anda yakin?</p>
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

{{-- Modal Konfirmasi Hapus Komentar --}}
<div id="deleteCommentModal" class="fixed inset-0 bg-[#080c12]/80 backdrop-blur-md z-1000 flex items-center justify-center opacity-0 invisible transition-all duration-300">
    <div class="bg-[#161b24] border border-white/7 rounded-2xl max-w-100 w-[90%] p-6 animate-modalIn">
        <div class="text-center">
            <div class="w-14 h-14 rounded-full bg-[#f87171]/10 border border-[#f87171]/20 flex items-center justify-center mx-auto mb-4">
                <i class="fa-regular fa-trash-can text-2xl text-[#f87171]"></i>
            </div>
            <h3 class="font-['DM_Serif_Display'] text-xl text-[#f0f4f8] mb-2">Hapus Komentar?</h3>
            <p class="text-white/45 text-sm mb-5">Komentar yang dihapus tidak dapat dikembalikan. Apakah Anda yakin?</p>
            <div class="flex gap-3">
                <button id="cancelDeleteCommentBtn" class="flex-1 px-4 py-2 rounded-xl bg-[#1c2333] border border-white/7 text-white/45 font-semibold text-sm hover:bg-[#242d3d] hover:text-[#f0f4f8] transition-all cursor-pointer">
                    Batal
                </button>
                <button id="confirmDeleteCommentBtn" class="flex-1 px-4 py-2 rounded-xl bg-[#f87171] text-white font-semibold text-sm hover:bg-[#ef4444] hover:-translate-y-px transition-all cursor-pointer">
                    <i class="fa-regular fa-trash-can mr-1.5 text-[11px]"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<div class="w-full max-w-160 mx-auto pb-16 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- Profile card --}}
    <div class="relative rounded-2xl overflow-hidden mb-1 shadow-[0_4px_24px_rgba(0,0,0,0.35)] animate-fadeUp">
        <img src="{{ asset('images/unsri_bg.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover brightness-[0.35] saturate-[0.6]">
        <div class="absolute inset-0 bg-linear-to-br from-[#080c12]/85 to-[#161b24]/70"></div>
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.14)_0%,transparent_70%)] pointer-events-none"></div>
        
        <div class="relative z-10 p-6 flex justify-between items-center gap-4">
            <div>
                <div class="font-['DM_Serif_Display'] text-[1.4rem] text-[#f0f4f8] mb-0.5">{{ auth()->user()->name }}</div>
                <div class="text-[0.8rem] text-white/45 mb-3">{{ auth()->user()->email }}</div>
                <div class="inline-flex items-center gap-1.5 bg-blue-500/10 border border-blue-500/20 rounded-full px-3 py-0.5 text-[0.72rem] font-semibold text-primary-400 tracking-wide">
                    <i class="fa-solid fa-users text-[10px]"></i>
                    {{ auth()->user()->connections()->count() }} Connections
                </div>
            </div>
            <img src="{{ asset('images/Logo_unsri.jpg') }}" alt="UNSRI" 
                 class="w-12 h-12 object-contain rounded-[0.625rem] border border-white/13 bg-white/5 p-1 shrink-0">
        </div>
    </div>

    {{-- Create post --}}
    <div class="flex items-center gap-2.5 my-5">
        <div class="flex-1 h-px bg-white/7"></div>
        <span class="text-[0.68rem] font-semibold tracking-[0.12em] uppercase text-white/22 whitespace-nowrap">
            <i class="fa-regular fa-pen-to-square mr-1.5 text-[10px]"></i>
            Buat Postingan
        </span>
        <div class="flex-1 h-px bg-white/7"></div>
    </div>

    <div class="bg-[#161b24] border border-white/7 rounded-2xl p-5 pb-4.5 mb-3 animate-fadeUp" style="animation-delay: 0.04s">
        <div class="flex items-center gap-2 mb-3.5">
            <div class="w-1.5 h-1.5 rounded-full bg-primary-400"></div>
            <span class="text-[0.68rem] font-bold tracking-widest uppercase text-white/22">
                <i class="fa-regular fa-file-lines mr-1.5"></i>
                New Post
            </span>
        </div>
        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Judul postingan…" required
                   class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-4 py-2.5 font-['DM_Sans'] text-sm text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all mb-2">
            <textarea name="content" placeholder="Bagikan sesuatu kepada komunitas…" required
                      class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-4 py-2.5 font-['DM_Sans'] text-sm text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all min-h-20 resize-none"></textarea>
            <button type="submit" 
                    class="mt-3 bg-primary-600 text-white border-none rounded-xl px-5 py-2 font-['DM_Sans'] text-[0.85rem] font-semibold cursor-pointer inline-flex items-center gap-2 tracking-wide hover:bg-primary-500 hover:-translate-y-px hover:shadow-[0_6px_16px_rgba(59,130,246,0.3)] active:translate-y-0 transition-all">
                <i class="fa-solid fa-paper-plane text-[11px]"></i>
                Publikasikan
            </button>
        </form>
    </div>

    {{-- Feed --}}
    <div class="flex items-center gap-2.5 my-5">
        <div class="flex-1 h-px bg-white/7"></div>
        <span class="text-[0.68rem] font-semibold tracking-[0.12em] uppercase text-white/22 whitespace-nowrap">
            <i class="fa-regular fa-newspaper mr-1.5 text-[10px]"></i>
            Postingan Terbaru
        </span>
        <div class="flex-1 h-px bg-white/7"></div>
    </div>

    @forelse($posts as $post)
    <div class="bg-[#161b24] border border-white/7 rounded-2xl p-4.5 pb-4 mb-3 hover:border-white/13 hover:shadow-[0_8px_28px_rgba(0,0,0,0.3)] transition-all animate-fadeUp post-card" id="post-card-{{ $post->id }}">
        
        {{-- Author row --}}
        <div class="flex justify-between items-start mb-3.5">
            <div class="flex items-center gap-2.5">
                {{-- Avatar bisa diklik ke profil --}}
                <a href="{{ route('profile.show', $post->user->id) }}" class="w-9 h-9 rounded-[0.625rem] bg-linear-to-br from-[#242d3d] to-[#2e3a4e] border border-white/13 flex items-center justify-center text-[0.75rem] font-bold text-primary-400 shrink-0 tracking-tight hover:scale-105 hover:border-blue-500/30 transition-all">
                    {{ strtoupper(substr($post->user->name, 0, 2)) }}
                </a>
                {{-- Nama bisa diklik ke profil --}}
                <a href="{{ route('profile.show', $post->user->id) }}" class="hover:text-primary-400 transition-all">
                    <div class="text-[0.875rem] font-semibold text-[#f0f4f8] leading-tight">{{ $post->user->name }}</div>
                    <div class="text-[0.7rem] text-white/22 mt-px">
                        <i class="fa-regular fa-clock text-[9px] mr-1"></i>
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </a>
            </div>

            @if(auth()->id() === $post->user_id)
            <div class="flex gap-1.5">
                <a href="{{ route('posts.edit', $post->id) }}" 
                   class="text-[0.72rem] font-semibold text-[#fbbf24] bg-[#fbbf24]/8 border border-[#fbbf24]/18 rounded-lg px-2.5 py-0.5 no-underline hover:bg-[#fbbf24]/15 transition-all">
                    <i class="fa-regular fa-pen-to-square text-[10px] mr-1"></i>Edit
                </a>
                <button type="button" 
                        onclick="showDeleteModal({{ $post->id }})"
                        class="text-[0.72rem] font-semibold text-[#f87171] bg-[#f87171]/7 border border-[#f87171]/18 rounded-lg px-2.5 py-0.5 cursor-pointer hover:bg-[#f87171]/15 transition-all">
                    <i class="fa-regular fa-trash-can text-[10px] mr-1"></i>Hapus
                </button>
            </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="font-['DM_Serif_Display'] text-[1.05rem] text-[#f0f4f8] mb-1.5 leading-tight">{{ $post->title }}</div>
        <div class="text-[0.85rem] text-white/45 leading-relaxed mb-3.5 whitespace-pre-wrap wrap-break-word">{{ $post->content }}</div>

        {{-- Footer with Like --}}
        <div class="flex items-center gap-2.5 pt-3 border-t border-white/7">
            <button type="button" 
                    onclick="toggleLike({{ $post->id }}, this)"
                    class="like-btn inline-flex items-center gap-1.5 bg-none border border-white/7 rounded-full px-3.5 py-1 font-['DM_Sans'] text-[0.78rem] font-medium cursor-pointer hover:border-primary-400/35 hover:text-primary-400 hover:bg-primary-400/6 transition-all {{ $post->is_liked_by_user ? 'text-[#60a5fa] border-[#60a5fa]/35' : 'text-white/45' }}">
                <i class="{{ $post->is_liked_by_user ? 'fa-solid fa-thumbs-up' : 'fa-regular fa-thumbs-up' }} text-[11px]"></i>
                <span class="like-count font-bold text-[#f0f4f8]">{{ $post->likes->count() }}</span>
            </button>
            <span class="text-[0.73rem] text-white/22">
                <i class="fa-regular fa-comment text-[10px] mr-1"></i>
                <span class="comment-count">{{ $post->comments->count() }}</span> komentar
            </span>
        </div>

        {{-- Comments Container --}}
        <div class="comments-container mt-3.5 pt-3 border-t border-white/7 flex flex-col gap-2" id="comments-{{ $post->id }}">
            @foreach($post->comments as $comment)
            <div class="flex gap-2 items-start comment-item group" id="comment-{{ $comment->id }}">
                {{-- Avatar comment user --}}
                <a href="{{ route('profile.show', $comment->user->id) }}" class="w-6.5 h-6.5 rounded-lg bg-[#242d3d] border border-white/7 flex items-center justify-center text-[0.6rem] font-bold text-white/45 shrink-0 mt-px hover:scale-105 hover:border-blue-500/30 transition-all">
                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                </a>
                <div class="bg-[#1c2333] border border-white/7 rounded-lg rounded-br-lg rounded-tl-lg px-3 py-1.5 text-[0.8rem] text-white/45 leading-relaxed flex-1">
                    {{-- Nama comment user bisa diklik --}}
                    <a href="{{ route('profile.show', $comment->user->id) }}" class="hover:text-primary-400 transition-all">
                        <strong class="block text-[0.72rem] font-bold text-primary-400 mb-0.5">{{ $comment->user->name }}</strong>
                    </a>
                    <span class="comment-text">{{ $comment->content }}</span>
                    <div class="edit-comment-form mt-2">
                        <input type="text" class="edit-comment-input w-full bg-[#1c2333] border border-white/10 rounded-xl px-3 py-1.5 text-sm text-[#f0f4f8]" value="{{ $comment->content }}">
                        <div class="flex gap-2 mt-2">
                            <button type="button" onclick="saveEditComment({{ $comment->id }}, this)" class="text-[0.68rem] text-[#4ade80] hover:text-[#4ade80]/80 transition-all">
                                <i class="fa-regular fa-check-circle mr-1"></i>Simpan
                            </button>
                            <button type="button" onclick="cancelEditComment({{ $comment->id }}, this)" class="text-[0.68rem] text-white/45 hover:text-white/70 transition-all">
                                <i class="fa-regular fa-circle-xmark mr-1"></i>Batal
                            </button>
                        </div>
                    </div>
                </div>
                @if(auth()->id() === $comment->user_id)
                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button type="button" onclick="editComment({{ $comment->id }})" class="text-white/30 hover:text-[#fbbf24] transition-all p-1" title="Edit komentar">
                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                    </button>
                    <button type="button" onclick="showDeleteCommentModal({{ $comment->id }})" class="text-white/30 hover:text-[#f87171] transition-all p-1" title="Hapus komentar">
                        <i class="fa-regular fa-trash-can text-[10px]"></i>
                    </button>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Comment Form --}}
        <form class="comment-form flex gap-2 mt-3" onsubmit="submitComment(event, {{ $post->id }})">
            @csrf
            <input type="text" name="content" placeholder="Tulis komentar…" required
                   class="comment-input flex-1 bg-[#1c2333] border border-white/7 rounded-full px-4 py-1.5 font-['DM_Sans'] text-[0.8rem] text-[#f0f4f8] placeholder-white/18 outline-none focus:border-primary-500 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/8 transition-all">
            <button type="submit" 
                    class="comment-submit w-8.5 h-8.5 rounded-full bg-primary-600 border-none cursor-pointer flex items-center justify-center shrink-0 hover:bg-primary-500 hover:scale-105 hover:shadow-[0_4px_12px_rgba(59,130,246,0.35)] transition-all">
                <i class="fa-solid fa-paper-plane text-white text-[11px]"></i>
            </button>
        </form>
    </div>
    @empty
    <div class="text-center py-14 px-5">
        <div class="text-3xl mb-2 opacity-40">
            <i class="fa-regular fa-newspaper"></i>
        </div>
        <div class="text-[0.875rem] text-white/22">Belum ada postingan. Jadilah yang pertama!</div>
    </div>
    @endforelse

</div>

<script>
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    
    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toastNotification');
        const icon = document.getElementById('toastIcon');
        const messageSpan = document.getElementById('toastMessage');
        
        toast.classList.remove('opacity-100', 'visible', 'translate-y-0');
        
        if (type === 'success') {
            icon.className = 'fa-regular fa-circle-check text-sm';
            toast.style.background = 'rgba(74,222,128,0.15)';
            toast.style.borderColor = 'rgba(74,222,128,0.3)';
            toast.style.color = '#4ade80';
        } else if (type === 'error') {
            icon.className = 'fa-regular fa-circle-xmark text-sm';
            toast.style.background = 'rgba(248,113,113,0.15)';
            toast.style.borderColor = 'rgba(248,113,113,0.3)';
            toast.style.color = '#f87171';
        } else {
            icon.className = 'fa-regular fa-circle-info text-sm';
            toast.style.background = 'rgba(96,165,250,0.15)';
            toast.style.borderColor = 'rgba(96,165,250,0.3)';
            toast.style.color = '#60a5fa';
        }
        
        messageSpan.textContent = message;
        
        setTimeout(() => {
            toast.classList.add('opacity-100', 'visible', 'translate-y-0');
        }, 10);
        
        setTimeout(() => {
            toast.classList.remove('opacity-100', 'visible', 'translate-y-0');
        }, 3000);
    }
    
    // LIKE FUNCTION
    async function toggleLike(postId, button) {
        // Disable button sementara
        button.disabled = true;
        
        // Simpan state sebelumnya untuk rollback jika error
        const wasLiked = button.classList.contains('text-[#60a5fa]');
        const icon = button.querySelector('i');
        const oldIconClass = icon.className;
        const likeCountSpan = button.querySelector('.like-count');
        const oldCount = parseInt(likeCountSpan.textContent);
        
        // Tambah animasi
        button.classList.add('liked-animation');
        setTimeout(() => {
            button.classList.remove('liked-animation');
        }, 300);
        
        // Optimistic update - update UI terlebih dahulu
        if (wasLiked) {
            // Unlike
            button.classList.remove('text-[#60a5fa]', 'border-[#60a5fa]/35');
            button.classList.add('text-white/45');
            icon.className = 'fa-regular fa-thumbs-up text-[11px]';
            likeCountSpan.textContent = oldCount - 1;
        } else {
            // Like
            button.classList.add('text-[#60a5fa]', 'border-[#60a5fa]/35');
            button.classList.remove('text-white/45');
            icon.className = 'fa-solid fa-thumbs-up text-[11px]';
            likeCountSpan.textContent = oldCount + 1;
        }
        
        try {
            const response = await fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update dengan data dari server untuk memastikan akurasi
                likeCountSpan.textContent = data.likes_count;
                
                if (data.liked) {
                    button.classList.add('text-[#60a5fa]', 'border-[#60a5fa]/35');
                    button.classList.remove('text-white/45');
                    icon.className = 'fa-solid fa-thumbs-up text-[11px]';
                } else {
                    button.classList.remove('text-[#60a5fa]', 'border-[#60a5fa]/35');
                    button.classList.add('text-white/45');
                    icon.className = 'fa-regular fa-thumbs-up text-[11px]';
                }
                
                showToast(data.liked ? 'Berhasil like!' : 'Like dihapus', data.liked ? 'success' : 'info');
            } else {
                // Rollback jika gagal
                if (wasLiked) {
                    button.classList.add('text-[#60a5fa]', 'border-[#60a5fa]/35');
                    button.classList.remove('text-white/45');
                    icon.className = 'fa-solid fa-thumbs-up text-[11px]';
                    likeCountSpan.textContent = oldCount;
                } else {
                    button.classList.remove('text-[#60a5fa]', 'border-[#60a5fa]/35');
                    button.classList.add('text-white/45');
                    icon.className = 'fa-regular fa-thumbs-up text-[11px]';
                    likeCountSpan.textContent = oldCount;
                }
                showToast(data.message || 'Terjadi kesalahan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            // Rollback jika error
            if (wasLiked) {
                button.classList.add('text-[#60a5fa]', 'border-[#60a5fa]/35');
                button.classList.remove('text-white/45');
                icon.className = 'fa-solid fa-thumbs-up text-[11px]';
                likeCountSpan.textContent = oldCount;
            } else {
                button.classList.remove('text-[#60a5fa]', 'border-[#60a5fa]/35');
                button.classList.add('text-white/45');
                icon.className = 'fa-regular fa-thumbs-up text-[11px]';
                likeCountSpan.textContent = oldCount;
            }
            showToast('Terjadi kesalahan pada server', 'error');
        } finally {
            // Enable button kembali
            button.disabled = false;
        }
    }
    
    // COMMENT FUNCTION
    async function submitComment(event, postId) {
        event.preventDefault();
        
        const form = event.target;
        const input = form.querySelector('.comment-input');
        const content = input.value.trim();
        
        if (!content) {
            showToast('Komentar tidak boleh kosong', 'error');
            return;
        }
        
        const submitBtn = form.querySelector('.comment-submit');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="spinner-border"></div>';
        
        try {
            const response = await fetch(`/posts/${postId}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ content: content })
            });
            
            const data = await response.json();
            
            if (data.success) {
                const userName = '{{ auth()->user()->name }}';
                const userAvatar = '{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}';
                const userId = '{{ auth()->id() }}';
                
                const commentsContainer = document.getElementById(`comments-${postId}`);
                const newComment = document.createElement('div');
                newComment.className = 'flex gap-2 items-start comment-item group';
                newComment.id = `comment-${data.comment.id}`;
                newComment.innerHTML = `
                    <a href="/profile/${userId}" class="w-6.5 h-6.5 rounded-lg bg-[#242d3d] border border-white/7 flex items-center justify-center text-[0.6rem] font-bold text-white/45 flex-shrink-0 mt-px hover:scale-105 hover:border-blue-500/30 transition-all">
                        ${userAvatar}
                    </a>
                    <div class="bg-[#1c2333] border border-white/7 rounded-lg rounded-br-lg rounded-tl-lg px-3 py-1.5 text-[0.8rem] text-white/45 leading-relaxed flex-1">
                        <a href="/profile/${userId}" class="hover:text-[#60a5fa] transition-all">
                            <strong class="block text-[0.72rem] font-bold text-[#60a5fa] mb-0.5">${escapeHtml(userName)}</strong>
                        </a>
                        <span class="comment-text">${escapeHtml(content)}</span>
                        <div class="edit-comment-form mt-2">
                            <input type="text" class="edit-comment-input w-full bg-[#1c2333] border border-white/10 rounded-xl px-3 py-1.5 text-sm text-[#f0f4f8]" value="${escapeHtml(content)}">
                            <div class="flex gap-2 mt-2">
                                <button type="button" onclick="saveEditComment(${data.comment.id}, this)" class="text-[0.68rem] text-[#4ade80] hover:text-[#4ade80]/80 transition-all">
                                    <i class="fa-regular fa-check-circle mr-1"></i>Simpan
                                </button>
                                <button type="button" onclick="cancelEditComment(${data.comment.id}, this)" class="text-[0.68rem] text-white/45 hover:text-white/70 transition-all">
                                    <i class="fa-regular fa-circle-xmark mr-1"></i>Batal
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" onclick="editComment(${data.comment.id})" class="text-white/30 hover:text-[#fbbf24] transition-all p-1" title="Edit komentar">
                            <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                        </button>
                        <button type="button" onclick="showDeleteCommentModal(${data.comment.id})" class="text-white/30 hover:text-[#f87171] transition-all p-1" title="Hapus komentar">
                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                        </button>
                    </div>
                `;
                
                commentsContainer.appendChild(newComment);
                
                const commentCountSpan = document.querySelector(`#post-card-${postId} .comment-count`);
                if (commentCountSpan) {
                    const currentCount = parseInt(commentCountSpan.textContent);
                    commentCountSpan.textContent = currentCount + 1;
                }
                
                input.value = '';
                showToast('Komentar berhasil ditambahkan!', 'success');
            } else {
                showToast(data.message || 'Gagal menambah komentar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        }
    }
    
    // EDIT COMMENT FUNCTIONS
    function editComment(commentId) {
        const commentDiv = document.getElementById(`comment-${commentId}`);
        if (!commentDiv) return;
        
        const commentText = commentDiv.querySelector('.comment-text');
        const editForm = commentDiv.querySelector('.edit-comment-form');
        
        commentText.style.display = 'none';
        editForm.style.display = 'block';
        
        const input = editForm.querySelector('.edit-comment-input');
        if (input) {
            input.focus();
            input.select();
        }
    }
    
    function cancelEditComment(commentId, button) {
        const commentDiv = document.getElementById(`comment-${commentId}`);
        if (!commentDiv) return;
        
        const commentText = commentDiv.querySelector('.comment-text');
        const editForm = commentDiv.querySelector('.edit-comment-form');
        const input = editForm.querySelector('.edit-comment-input');
        const originalText = commentText.textContent.trim();
        
        input.value = originalText;
        commentText.style.display = 'inline';
        editForm.style.display = 'none';
    }
    
    async function saveEditComment(commentId, button) {
        const commentDiv = document.getElementById(`comment-${commentId}`);
        if (!commentDiv) return;
        
        const editForm = commentDiv.querySelector('.edit-comment-form');
        const input = editForm.querySelector('.edit-comment-input');
        const newContent = input.value.trim();
        const commentText = commentDiv.querySelector('.comment-text');
        
        if (!newContent) {
            showToast('Komentar tidak boleh kosong', 'error');
            return;
        }
        
        const originalText = button.innerHTML;
        button.innerHTML = '<div class="spinner-border" style="width:12px;height:12px;"></div>';
        button.disabled = true;
        
        try {
            const response = await fetch(`/comments/${commentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ content: newContent })
            });
            
            const data = await response.json();
            
            if (data.success) {
                commentText.textContent = newContent;
                input.value = newContent;
                commentText.style.display = 'inline';
                editForm.style.display = 'none';
                showToast('Komentar berhasil diperbarui!', 'success');
            } else {
                showToast(data.message || 'Gagal memperbarui komentar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        } finally {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }
    
    // DELETE COMMENT FUNCTIONS
    let deleteCommentId = null;
    
    function showDeleteCommentModal(commentId) {
        deleteCommentId = commentId;
        const modal = document.getElementById('deleteCommentModal');
        modal.classList.add('opacity-100', 'visible');
        modal.classList.remove('opacity-0', 'invisible');
    }
    
    function hideDeleteCommentModal() {
        const modal = document.getElementById('deleteCommentModal');
        modal.classList.remove('opacity-100', 'visible');
        modal.classList.add('opacity-0', 'invisible');
        deleteCommentId = null;
    }
    
    async function confirmDeleteComment() {
        if (!deleteCommentId) return;
        
        const modal = document.getElementById('deleteCommentModal');
        modal.classList.remove('opacity-100', 'visible');
        modal.classList.add('opacity-0', 'invisible');
        
        try {
            const response = await fetch(`/comments/${deleteCommentId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                const commentElement = document.getElementById(`comment-${deleteCommentId}`);
                if (commentElement) {
                    const postCard = commentElement.closest('.post-card');
                    commentElement.remove();
                    
                    if (postCard) {
                        const commentCountSpan = postCard.querySelector('.comment-count');
                        if (commentCountSpan) {
                            const currentCount = parseInt(commentCountSpan.textContent);
                            commentCountSpan.textContent = currentCount - 1;
                        }
                    }
                }
                showToast('Komentar berhasil dihapus!', 'success');
            } else {
                showToast(data.message || 'Gagal menghapus komentar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // DELETE POST FUNCTIONS
    let deletePostId = null;
    let deleteForm = null;
    
    function showDeleteModal(postId) {
        deletePostId = postId;
        deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = '/posts/' + postId;
        deleteForm.innerHTML = '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                               '<input type="hidden" name="_method" value="DELETE">';
        
        const modal = document.getElementById('deleteModal');
        modal.classList.add('opacity-100', 'visible');
        modal.classList.remove('opacity-0', 'invisible');
    }
    
    function hideModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('opacity-100', 'visible');
        modal.classList.add('opacity-0', 'invisible');
        deletePostId = null;
        deleteForm = null;
    }
    
    function confirmDelete() {
        if (deleteForm && deletePostId) {
            document.body.appendChild(deleteForm);
            deleteForm.submit();
        }
        hideModal();
    }
    
    // Event listeners
    document.getElementById('cancelDeleteBtn')?.addEventListener('click', hideModal);
    document.getElementById('confirmDeleteBtn')?.addEventListener('click', confirmDelete);
    document.getElementById('cancelDeleteCommentBtn')?.addEventListener('click', hideDeleteCommentModal);
    document.getElementById('confirmDeleteCommentBtn')?.addEventListener('click', confirmDeleteComment);
    
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) hideModal();
    });
    
    document.getElementById('deleteCommentModal')?.addEventListener('click', function(e) {
        if (e.target === this) hideDeleteCommentModal();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('deleteModal')?.classList.contains('opacity-100')) hideModal();
            if (document.getElementById('deleteCommentModal')?.classList.contains('opacity-100')) hideDeleteCommentModal();
        }
    });
    
    // Session messages
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
</script>

@endsection