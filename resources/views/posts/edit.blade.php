@extends('layouts.app')

@section('content')

<style>
    /* Animations only - Tailwind doesn't have these */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes epShake {
        0%,100% { transform: translateX(0); }
        20%,60%  { transform: translateX(-4px); }
        40%,80%  { transform: translateX(4px); }
    }
    
    @keyframes epSpin { to { transform: rotate(360deg); } }
    
    .animate-fadeUp { animation: fadeUp 0.45s ease both; }
    .animate-fadeUp-delay { animation: fadeUp 0.5s 0.06s ease both; }
    .animate-fadeUp-late { animation: fadeUp 0.5s 0.1s ease both; }
    .animate-shake { animation: epShake 0.4s ease; }
    .animate-spin-custom { animation: epSpin 0.7s linear infinite; }
    
    /* Auto-grow textarea */
    .ep-textarea-auto {
        min-height: 160px;
        line-height: 1.7;
    }
</style>

{{-- Loading overlay --}}
<div id="epOverlay" class="fixed inset-0 bg-[#080c12]/65 backdrop-blur-md z-9999 flex flex-col items-center justify-center gap-3.5 opacity-0 invisible transition-opacity duration-250">
    <div class="w-9 h-9 border-[2.5px] border-white/12 border-t-primary-400 rounded-full animate-spin-custom"></div>
    <div id="epOverlayText" class="font-['DM_Sans'] text-[0.875rem] font-medium text-white/45">Menyimpan perubahan…</div>
</div>

{{-- Toast --}}
<div id="epToast" class="fixed bottom-7 left-1/2 -translate-x-1/2 translate-y-2.5 bg-[#1c2333] border border-white/13 text-[#f0f4f8] font-['DM_Sans'] text-[0.82rem] font-medium py-2 px-5 rounded-full shadow-[0_8px_24px_rgba(0,0,0,0.4)] opacity-0 pointer-events-none transition-all duration-250 z-10000 whitespace-nowrap"></div>

<div class="w-full max-w-160 mx-auto pb-16 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- Top bar --}}
    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-1.5 no-underline font-['DM_Sans'] text-[0.8rem] font-semibold text-white/45 px-2.5 py-1.5 rounded-full border border-white/7 bg-[#161b24] hover:text-[#f0f4f8] hover:border-white/13 hover:-translate-x-0.5 transition-all duration-200">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            Kembali ke Feed
        </a>
        <span class="text-[0.7rem] text-white/22 tracking-wide hidden sm:inline">Feed / Edit Postingan</span>
    </div>

    {{-- Header card --}}
    <div class="relative rounded-2xl overflow-hidden mb-1 shadow-[0_4px_24px_rgba(0,0,0,0.35)] animate-fadeUp">
        <img src="{{ asset('images/unsri_bg.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover brightness-[0.3] saturate-[0.6]">
        <div class="absolute inset-0 bg-linear-to-br from-[#080c12]/88 to-[#161b24]/72"></div>
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.12)_0%,transparent_70%)] pointer-events-none"></div>
        
        <div class="relative z-10 p-5 flex items-center gap-3.5">
            <div class="w-10.5 h-10.5 rounded-[0.625rem] bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                <i class="fa-regular fa-pen-to-square text-primary-400 text-base"></i>
            </div>
            <div>
                <div class="font-['DM_Serif_Display'] text-[1.35rem] text-[#f0f4f8] mb-0.5 leading-tight">Edit Postingan</div>
                <div class="text-[0.78rem] text-white/45">Perbarui judul dan isi postinganmu</div>
            </div>
        </div>
    </div>

    {{-- Section head --}}
    <div class="flex items-center gap-2.5 my-5">
        <div class="flex-1 h-px bg-white/7"></div>
        <span class="text-[0.68rem] font-semibold tracking-[0.12em] uppercase text-white/22 whitespace-nowrap">
            <i class="fa-regular fa-file-lines mr-1.5 text-[10px]"></i>
            Detail Postingan
        </span>
        <div class="flex-1 h-px bg-white/7"></div>
    </div>

    {{-- Edit card --}}
    <div class="bg-[#161b24] border border-white/7 rounded-2xl p-5 animate-fadeUp-delay">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-1.5 h-1.5 rounded-full bg-primary-400"></div>
            <span class="text-[0.68rem] font-bold tracking-widest uppercase text-white/22">
                <i class="fa-regular fa-pen-to-square mr-1.5 text-[10px]"></i>
                Edit Post
            </span>
        </div>

        @if($errors->any())
        <div class="bg-[#f87171]/7 border border-[#f87171]/20 rounded-xl p-3 mb-4 animate-shake">
            <ul class="list-none">
                @foreach($errors->all() as $error)
                    <li class="text-[0.8rem] text-[#f87171] font-medium py-0.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle text-[5px] text-[#f87171]"></i>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="epForm" action="{{ route('posts.update', $post->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="mb-3.5">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[0.7rem] font-bold tracking-widest uppercase text-white/22">Judul</span>
                    <span id="epTitleCount" class="text-[0.7rem] text-white/22 font-mono tabular-nums transition-colors duration-150">{{ strlen(old('title', $post->title)) }} / 150</span>
                </div>
                <input type="text" name="title" id="epTitle"
                       class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-4 py-2.5 font-['DM_Sans'] text-[0.875rem] text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all"
                       placeholder="Tulis judul yang menarik…"
                       value="{{ old('title', $post->title) }}"
                       maxlength="150" autocomplete="off" required>
            </div>

            <div class="h-px bg-white/7 my-4"></div>

            {{-- Content --}}
            <div class="mb-0">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[0.7rem] font-bold tracking-widest uppercase text-white/22">Isi Postingan</span>
                    <span id="epContentCount" class="text-[0.7rem] text-white/22 font-mono tabular-nums transition-colors duration-150">{{ strlen(old('content', $post->content)) }} / 2000</span>
                </div>
                <textarea name="content" id="epContent"
                          class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-4 py-2.5 font-['DM_Sans'] text-[0.875rem] text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all resize-none ep-textarea-auto"
                          placeholder="Bagikan sesuatu kepada komunitas…"
                          maxlength="2000" required>{{ old('content', $post->content) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 pt-4 mt-4 border-t border-white/7 sm:flex-row flex-col">
                <a href="{{ route('posts.index') }}" class="inline-flex items-center justify-center gap-1.5 font-['DM_Sans'] text-[0.85rem] font-semibold py-2.5 px-5 rounded-xl border border-white/7 bg-[#1c2333] text-white/45 no-underline hover:bg-[#242d3d] hover:text-[#f0f4f8] hover:border-white/13 hover:-translate-y-px transition-all duration-200 sm:w-auto w-full">
                    <i class="fa-regular fa-circle-xmark mr-1"></i>
                    Batal
                </a>
                <button type="submit" id="epSubmit" class="flex-1 flex items-center justify-center gap-1.5 font-['DM_Sans'] text-[0.85rem] font-semibold py-2.5 px-5 rounded-xl bg-primary-600 text-white border-none cursor-pointer tracking-wide hover:bg-primary-500 hover:-translate-y-px hover:shadow-[0_6px_16px_rgba(59,130,246,0.3)] active:translate-y-0 transition-all relative overflow-hidden group">
                    <i class="fa-regular fa-floppy-disk text-[11px] group-hover:rotate-12 transition-transform"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Danger zone section --}}
    <div class="flex items-center gap-2.5 mt-5 mb-3">
        <div class="flex-1 h-px bg-white/7"></div>
        <span class="text-[0.68rem] font-semibold tracking-[0.12em] uppercase text-white/22 whitespace-nowrap">
            <i class="fa-solid fa-triangle-exclamation mr-1.5 text-[10px]"></i>
            Danger Zone
        </span>
        <div class="flex-1 h-px bg-white/7"></div>
    </div>

    <div class="bg-[#f87171]/5 border border-[#f87171]/15 rounded-2xl p-5 flex justify-between gap-4 animate-fadeUp-late sm:flex-row flex-col sm:items-center items-start">
        <div>
            <div class="text-[0.85rem] font-bold text-[#f87171] mb-0.5">Hapus Postingan</div>
            <div class="text-[0.72rem] text-white/22 leading-relaxed">Tindakan ini permanen dan tidak dapat dibatalkan</div>
        </div>
        <form id="epDeleteForm" action="{{ route('posts.destroy', $post->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" id="epDeleteBtn" onclick="epConfirmDelete()" class="inline-flex items-center gap-1.5 font-['DM_Sans'] text-[0.8rem] font-semibold py-2 px-4 rounded-full bg-[#f87171]/8 text-[#f87171] border border-[#f87171]/20 cursor-pointer hover:bg-[#f87171] hover:text-white hover:border-[#f87171] hover:shadow-[0_4px_14px_rgba(248,113,113,0.3)] hover:-translate-y-px active:translate-y-0 transition-all whitespace-nowrap shrink-0">
                <i class="fa-regular fa-trash-can text-[11px]"></i>
                Hapus
            </button>
        </form>
    </div>

</div>

<script>
(function () {
    /* ── Char counter with warning colors ───────────────────────── */
    function initCounter(inputId, counterId, max) {
        const input   = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        if (!input || !counter) return;

        function update() {
            const n = input.value.length;
            counter.textContent = n + ' / ' + max;
            counter.classList.remove('text-[#fbbf24]', 'text-[#f87171]');
            if (n >= max)            counter.classList.add('text-[#f87171]');
            else if (n > max * 0.88) counter.classList.add('text-[#fbbf24]');
        }

        input.addEventListener('input', update);
        update();
    }

    initCounter('epTitle', 'epTitleCount', 150);
    initCounter('epContent', 'epContentCount', 2000);

    /* ── Form submit with loading overlay ────────────────────────── */
    const form    = document.getElementById('epForm');
    const submit  = document.getElementById('epSubmit');
    const overlay = document.getElementById('epOverlay');
    const ovText  = document.getElementById('epOverlayText');

    if (form) {
        form.addEventListener('submit', function () {
            submit.classList.add('opacity-65', 'pointer-events-none');
            submit.disabled = true;
            ovText.textContent = 'Menyimpan perubahan…';
            overlay.classList.remove('opacity-0', 'invisible');
            overlay.classList.add('opacity-100', 'visible');
        });
    }

    /* ── Delete confirm (double-click pattern) ───────────────────── */
    var _confirming = false;
    var _timer      = null;

    window.epConfirmDelete = function () {
        const btn = document.getElementById('epDeleteBtn');

        if (!_confirming) {
            _confirming = true;
            btn.innerHTML = '<i class="fa-regular fa-circle-exclamation text-[11px]"></i> Yakin hapus?';

            _timer = setTimeout(function () {
                _confirming = false;
                btn.innerHTML = '<i class="fa-regular fa-trash-can text-[11px]"></i> Hapus';
            }, 3000);

        } else {
            clearTimeout(_timer);
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-regular fa-hourglass-half text-[11px] animate-spin"></i> Menghapus…';
            ovText.textContent = 'Menghapus postingan…';
            overlay.classList.remove('opacity-0', 'invisible');
            overlay.classList.add('opacity-100', 'visible');
            document.getElementById('epDeleteForm').submit();
        }
    };

    /* ── Scroll to errors ───────────────────────────────────────── */
    const errBox = document.querySelector('.animate-shake');
    if (errBox) errBox.scrollIntoView({ behavior: 'smooth', block: 'center' });

    /* ── Auto-grow textarea ─────────────────────────────────────── */
    const ta = document.getElementById('epContent');
    if (ta) {
        function autoGrow() {
            ta.style.height = 'auto';
            ta.style.height = Math.max(160, ta.scrollHeight) + 'px';
        }
        ta.addEventListener('input', autoGrow);
        autoGrow();
    }
})();
</script>

@endsection