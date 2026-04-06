@extends('layouts.app')

@section('content')

<style>
    /* Hanya animasi yang tidak tersedia di Tailwind */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeUp {
        animation: fadeUp 0.5s ease both;
    }
    
    .post-card:nth-child(1) { animation-delay: 0.04s; }
    .post-card:nth-child(2) { animation-delay: 0.08s; }
    .post-card:nth-child(3) { animation-delay: 0.12s; }
    .post-card:nth-child(4) { animation-delay: 0.16s; }
    .post-card:nth-child(5) { animation-delay: 0.20s; }
</style>

<div class="w-full max-w-[640px] mx-auto pb-16 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- ── Profile card ────────────────────────────────────── --}}
    <div class="relative rounded-2xl overflow-hidden mb-1 shadow-[0_4px_24px_rgba(0,0,0,0.35)] animate-fadeUp">
        <img src="{{ asset('images/unsri_bg.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover brightness-[0.35] saturate-[0.6]">
        <div class="absolute inset-0 bg-gradient-to-br from-[#080c12]/85 to-[#161b24]/70"></div>
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.14)_0%,transparent_70%)] pointer-events-none"></div>
        
        <div class="relative z-10 p-6 flex justify-between items-center gap-4">
            <div>
                <div class="font-['DM_Serif_Display'] text-[1.4rem] text-[#f0f4f8] mb-0.5">{{ auth()->user()->name }}</div>
                <div class="text-[0.8rem] text-white/45 mb-3">{{ auth()->user()->email }}</div>
                <div class="inline-flex items-center gap-1.5 bg-blue-500/10 border border-blue-500/20 rounded-full px-3 py-0.5 text-[0.72rem] font-semibold text-[#60a5fa] tracking-wide">
                    <i class="fa-solid fa-users text-[10px]"></i>
                    {{ auth()->user()->connections()->count() }} Connections
                </div>
            </div>
            <img src="{{ asset('images/Logo_unsri.jpg') }}" alt="UNSRI" 
                 class="w-12 h-12 object-contain rounded-[0.625rem] border border-white/13 bg-white/5 p-1 flex-shrink-0">
        </div>
    </div>

    {{-- ── Create post ──────────────────────────────────────── --}}
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
            <div class="w-1.5 h-1.5 rounded-full bg-[#60a5fa]"></div>
            <span class="text-[0.68rem] font-bold tracking-[0.1em] uppercase text-white/22">
                <i class="fa-regular fa-file-lines mr-1.5"></i>
                New Post
            </span>
        </div>
        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Judul postingan…" required
                   class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-4 py-2.5 font-['DM_Sans'] text-sm text-[#f0f4f8] placeholder-white/20 outline-none focus:border-[#3b82f6] focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all mb-2">
            <textarea name="content" placeholder="Bagikan sesuatu kepada komunitas…" required
                      class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-4 py-2.5 font-['DM_Sans'] text-sm text-[#f0f4f8] placeholder-white/20 outline-none focus:border-[#3b82f6] focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all min-h-[80px] resize-none"></textarea>
            <button type="submit" 
                    class="mt-3 bg-[#2563eb] text-white border-none rounded-xl px-5 py-2 font-['DM_Sans'] text-[0.85rem] font-semibold cursor-pointer inline-flex items-center gap-2 tracking-wide hover:bg-[#3b82f6] hover:-translate-y-px hover:shadow-[0_6px_16px_rgba(59,130,246,0.3)] active:translate-y-0 transition-all">
                <i class="fa-solid fa-paper-plane text-[11px]"></i>
                Publikasikan
            </button>
        </form>
    </div>

    {{-- ── Feed ────────────────────────────────────────────── --}}
    <div class="flex items-center gap-2.5 my-5">
        <div class="flex-1 h-px bg-white/7"></div>
        <span class="text-[0.68rem] font-semibold tracking-[0.12em] uppercase text-white/22 whitespace-nowrap">
            <i class="fa-regular fa-newspaper mr-1.5 text-[10px]"></i>
            Postingan Terbaru
        </span>
        <div class="flex-1 h-px bg-white/7"></div>
    </div>

    @forelse($posts as $post)
    <div class="bg-[#161b24] border border-white/7 rounded-2xl p-4.5 pb-4 mb-3 hover:border-white/13 hover:shadow-[0_8px_28px_rgba(0,0,0,0.3)] transition-all animate-fadeUp post-card">
        
        {{-- Author row --}}
        <div class="flex justify-between items-start mb-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-[0.625rem] bg-gradient-to-br from-[#242d3d] to-[#2e3a4e] border border-white/13 flex items-center justify-center text-[0.75rem] font-bold text-[#60a5fa] flex-shrink-0 tracking-tight">
                    {{ strtoupper(substr($post->user->name, 0, 2)) }}
                </div>
                <div>
                    <div class="text-[0.875rem] font-semibold text-[#f0f4f8] leading-tight">{{ $post->user->name }}</div>
                    <div class="text-[0.7rem] text-white/22 mt-px">
                        <i class="fa-regular fa-clock text-[9px] mr-1"></i>
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            @if(auth()->id() === $post->user_id)
            <div class="flex gap-1.5">
                <a href="{{ route('posts.edit', $post->id) }}" 
                   class="text-[0.72rem] font-semibold text-[#fbbf24] bg-[#fbbf24]/8 border border-[#fbbf24]/18 rounded-lg px-2.5 py-0.5 no-underline hover:bg-[#fbbf24]/15 transition-all">
                    <i class="fa-regular fa-pen-to-square text-[10px] mr-1"></i>Edit
                </a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus post ini?')" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-[0.72rem] font-semibold text-[#f87171] bg-[#f87171]/7 border border-[#f87171]/18 rounded-lg px-2.5 py-0.5 cursor-pointer hover:bg-[#f87171]/15 transition-all">
                        <i class="fa-regular fa-trash-can text-[10px] mr-1"></i>Hapus
                    </button>
                </form>
            </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="font-['DM_Serif_Display'] text-[1.05rem] text-[#f0f4f8] mb-1.5 leading-tight">{{ $post->title }}</div>
        <div class="text-[0.85rem] text-white/45 leading-relaxed mb-3.5 whitespace-pre-wrap break-words">{{ $post->content }}</div>

        {{-- Footer --}}
        <div class="flex items-center gap-2.5 pt-3 border-t border-white/7">
            <form action="{{ route('posts.like', $post->id) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 bg-none border border-white/7 rounded-full px-3.5 py-1 font-['DM_Sans'] text-[0.78rem] font-medium text-white/45 cursor-pointer hover:border-[#60a5fa]/35 hover:text-[#60a5fa] hover:bg-[#60a5fa]/6 transition-all">
                    <i class="fa-regular fa-thumbs-up text-[11px]"></i>
                    <span class="font-bold text-[#f0f4f8]">{{ $post->likes->count() }}</span>
                </button>
            </form>
            <span class="text-[0.73rem] text-white/22">
                <i class="fa-regular fa-comment text-[10px] mr-1"></i>
                {{ $post->comments->count() }} komentar
            </span>
        </div>

        {{-- Comments --}}
        @if($post->comments->count() > 0)
        <div class="mt-3.5 pt-3 border-t border-white/7 flex flex-col gap-2">
            @foreach($post->comments as $comment)
            <div class="flex gap-2 items-start">
                <div class="w-6.5 h-6.5 rounded-lg bg-[#242d3d] border border-white/7 flex items-center justify-center text-[0.6rem] font-bold text-white/45 flex-shrink-0 mt-px">
                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                </div>
                <div class="bg-[#1c2333] border border-white/7 rounded-lg rounded-br-lg rounded-tl-lg px-3 py-1.5 text-[0.8rem] text-white/45 leading-relaxed flex-1">
                    <strong class="block text-[0.72rem] font-bold text-[#60a5fa] mb-0.5">{{ $comment->user->name }}</strong>
                    {{ $comment->content }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Comment form --}}
        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="flex gap-2 mt-3">
            @csrf
            <input type="text" name="content" placeholder="Tulis komentar…" required
                   class="flex-1 bg-[#1c2333] border border-white/7 rounded-full px-4 py-1.5 font-['DM_Sans'] text-[0.8rem] text-[#f0f4f8] placeholder-white/18 outline-none focus:border-[#3b82f6] focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/8 transition-all">
            <button type="submit" 
                    class="w-8.5 h-8.5 rounded-full bg-[#2563eb] border-none cursor-pointer flex items-center justify-center flex-shrink-0 hover:bg-[#3b82f6] hover:scale-105 hover:shadow-[0_4px_12px_rgba(59,130,246,0.35)] transition-all">
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

@endsection