@extends('layouts.app')

@section('content')

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeUp {
        animation: fadeUp 0.45s ease both;
    }
</style>

<div class="w-full max-w-[680px] mx-auto px-4 pb-16 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- HEADER --}}
    <div class="flex items-center gap-3.5 mb-6 animate-fadeUp opacity-0" style="animation-delay: 0s">
        <a href="{{ route('profile.index') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-[0.625rem] bg-[#161b24] border border-white/7 text-white/45 no-underline hover:border-white/18 hover:text-[#f0f4f8] transition-all">
            <i class="fa-solid fa-arrow-left text-[12px]"></i>
        </a>
        <div>
            <div class="font-['DM_Serif_Display'] text-[1.35rem] text-[#f0f4f8] leading-none">Edit Profil</div>
            <div class="text-[0.75rem] text-white/30 mt-0.5">Perbarui informasi akunmu</div>
        </div>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-[#4ade80]/10 border border-[#4ade80]/25 rounded-xl p-3 mb-4 text-[0.85rem] text-[#4ade80] animate-fadeUp opacity-0" style="animation-delay: 0s">
        <i class="fa-solid fa-circle-check text-[14px] flex-shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- ERROR ALERT --}}
    @if($errors->any())
    <div class="flex items-center gap-3 bg-[#f87171]/10 border border-[#f87171]/25 rounded-xl p-3 mb-4 text-[0.85rem] text-[#f87171] animate-fadeUp opacity-0" style="animation-delay: 0s">
        <i class="fa-solid fa-circle-exclamation text-[14px] flex-shrink-0"></i>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- INFO DASAR --}}
        <div class="bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden mb-3 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
            <div class="flex items-center gap-1.5 px-5 py-3.5 border-b border-white/7 text-[0.68rem] font-bold tracking-[0.1em] uppercase text-white/22">
                <span class="w-1.5 h-1.5 rounded-full bg-[#60a5fa]"></span>
                Informasi Dasar
            </div>
            <div class="p-5">

                {{-- Nama Lengkap --}}
                <div class="mb-4">
                    <label class="block text-[0.75rem] font-semibold text-white/35 tracking-[0.06em] uppercase mb-1.5">
                        <i class="fa-regular fa-user mr-1 text-[10px]"></i>Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-3.5 py-2.5 font-['DM_Sans'] text-[0.88rem] text-[#f0f4f8] placeholder-white/18 outline-none focus:border-blue-500/45 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all"
                           placeholder="Nama lengkap kamu" required>
                    @error('name')
                        <div class="flex items-center gap-1 text-[0.72rem] text-[#f87171] mt-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Angkatan (Dropdown) --}}
                <div class="mb-4">
                    <label class="block text-[0.75rem] font-semibold text-white/35 tracking-[0.06em] uppercase mb-1.5">
                        <i class="fa-regular fa-calendar-alt mr-1 text-[10px]"></i>Angkatan
                    </label>
                    <select name="angkatan" 
                            class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-3.5 py-2.5 font-['DM_Sans'] text-[0.88rem] text-[#f0f4f8] outline-none focus:border-blue-500/45 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all cursor-pointer">
                        <option value="" disabled {{ !$user->angkatan ? 'selected' : '' }}>Pilih Angkatan</option>
                        @php
                            $currentYear = date('Y');
                            $startYear = 2020;
                        @endphp
                        @for($year = $currentYear; $year >= $startYear; $year--)
                            <option value="{{ $year }}" {{ old('angkatan', $user->angkatan) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                        <option value="Alumni" {{ old('angkatan', $user->angkatan) == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="Dosen" {{ old('angkatan', $user->angkatan) == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="Umum" {{ old('angkatan', $user->angkatan) == 'Umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                    @error('angkatan')
                        <div class="flex items-center gap-1 text-[0.72rem] text-[#f87171] mt-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Program Studi (Dropdown) --}}
                <div class="mb-4">
                    <label class="block text-[0.75rem] font-semibold text-white/35 tracking-[0.06em] uppercase mb-1.5">
                        <i class="fa-solid fa-graduation-cap mr-1 text-[10px]"></i>Program Studi
                    </label>
                    <select name="prodi" 
                            class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-3.5 py-2.5 font-['DM_Sans'] text-[0.88rem] text-[#f0f4f8] outline-none focus:border-blue-500/45 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all cursor-pointer">
                        <option value="" disabled {{ !$user->prodi ? 'selected' : '' }}>Pilih Program Studi</option>
                        <optgroup label="Fakultas Ilmu Komputer">
                            <option value="Teknik Informatika" {{ old('prodi', $user->prodi) == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                            <option value="Sistem Informasi" {{ old('prodi', $user->prodi) == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                            <option value="Manajemen Informatika" {{ old('prodi', $user->prodi) == 'Manajemen Informatika' ? 'selected' : '' }}>Manajemen Informatika</option>
                            <option value="Teknik Komputer" {{ old('prodi', $user->prodi) == 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
                            <option value="Data Science" {{ old('prodi', $user->prodi) == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                        </optgroup>
                        <optgroup label="Fakultas Teknik">
                            <option value="Teknik Sipil" {{ old('prodi', $user->prodi) == 'Teknik Sipil' ? 'selected' : '' }}>Teknik Sipil</option>
                            <option value="Teknik Mesin" {{ old('prodi', $user->prodi) == 'Teknik Mesin' ? 'selected' : '' }}>Teknik Mesin</option>
                            <option value="Teknik Elektro" {{ old('prodi', $user->prodi) == 'Teknik Elektro' ? 'selected' : '' }}>Teknik Elektro</option>
                            <option value="Teknik Kimia" {{ old('prodi', $user->prodi) == 'Teknik Kimia' ? 'selected' : '' }}>Teknik Kimia</option>
                            <option value="Arsitektur" {{ old('prodi', $user->prodi) == 'Arsitektur' ? 'selected' : '' }}>Arsitektur</option>
                        </optgroup>
                        <optgroup label="Fakultas Ekonomi">
                            <option value="Manajemen" {{ old('prodi', $user->prodi) == 'Manajemen' ? 'selected' : '' }}>Manajemen</option>
                            <option value="Akuntansi" {{ old('prodi', $user->prodi) == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                            <option value="Ekonomi Pembangunan" {{ old('prodi', $user->prodi) == 'Ekonomi Pembangunan' ? 'selected' : '' }}>Ekonomi Pembangunan</option>
                        </optgroup>
                        <optgroup label="Lainnya">
                            <option value="Dosen" {{ old('prodi', $user->prodi) == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="Alumni" {{ old('prodi', $user->prodi) == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                            <option value="Umum" {{ old('prodi', $user->prodi) == 'Umum' ? 'selected' : '' }}>Umum</option>
                            <option value="Lainnya" {{ old('prodi', $user->prodi) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </optgroup>
                    </select>
                    @error('prodi')
                        <div class="flex items-center gap-1 text-[0.72rem] text-[#f87171] mt-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Bio --}}
                <div>
                    <label class="block text-[0.75rem] font-semibold text-white/35 tracking-[0.06em] uppercase mb-1.5">
                        <i class="fa-regular fa-file-lines mr-1 text-[10px]"></i>Bio
                    </label>
                    <textarea name="bio" id="bio"
                              class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-3.5 py-2.5 font-['DM_Sans'] text-[0.88rem] text-[#f0f4f8] placeholder-white/18 outline-none focus:border-blue-500/45 focus:bg-[#242d3d] focus:ring-2 focus:ring-blue-500/10 transition-all min-h-[100px] resize-vertical"
                              placeholder="Ceritakan sedikit tentang dirimu…">{{ old('bio', $user->bio) }}</textarea>
                    <div class="text-[0.7rem] text-white/20 mt-1.5" id="bioCounter">0 / 500 karakter</div>
                    @error('bio')
                        <div class="flex items-center gap-1 text-[0.72rem] text-[#f87171] mt-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- EMAIL (readonly) --}}
        <div class="bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden mb-3 animate-fadeUp opacity-0" style="animation-delay: 0.1s">
            <div class="flex items-center gap-1.5 px-5 py-3.5 border-b border-white/7 text-[0.68rem] font-bold tracking-[0.1em] uppercase text-white/22">
                <span class="w-1.5 h-1.5 rounded-full bg-[#60a5fa]"></span>
                Akun
            </div>
            <div class="p-5">
                <div>
                    <label class="block text-[0.75rem] font-semibold text-white/35 tracking-[0.06em] uppercase mb-1.5">
                        <i class="fa-regular fa-envelope mr-1 text-[10px]"></i>Email
                    </label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full bg-[#1c2333] border border-white/7 rounded-xl px-3.5 py-2.5 font-['DM_Sans'] text-[0.88rem] text-[#f0f4f8] opacity-40 cursor-not-allowed">
                    <div class="text-[0.7rem] text-white/20 mt-1.5">Email tidak dapat diubah</div>
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="flex gap-3 mt-4 animate-fadeUp opacity-0" style="animation-delay: 0.15s">
            <a href="{{ route('profile.index') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 bg-transparent text-white/35 border border-white/10 rounded-xl px-5 py-2.5 font-['DM_Sans'] text-[0.88rem] font-semibold no-underline hover:border-white/20 hover:text-[#f0f4f8] transition-all">
                <i class="fa-solid fa-xmark text-[11px]"></i>
                Batal
            </a>
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 bg-[#2563eb] text-white border-none rounded-xl px-5 py-2.5 font-['DM_Sans'] text-[0.88rem] font-semibold cursor-pointer hover:bg-[#3b82f6] hover:-translate-y-px hover:shadow-[0_6px_16px_rgba(59,130,246,0.3)] active:translate-y-0 transition-all">
                <i class="fa-solid fa-floppy-disk text-[12px]"></i>
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<script>
    // Counter bio
    const bioEl = document.getElementById('bio');
    const counterEl = document.getElementById('bioCounter');
    if (bioEl && counterEl) {
        function updateCounter() {
            const len = bioEl.value.length;
            counterEl.textContent = len + ' / 500 karakter';
            counterEl.style.color = len > 480 ? '#fbbf24' : 'rgba(255,255,255,0.2)';
        }
        bioEl.addEventListener('input', updateCounter);
        updateCounter();
    }
</script>

@endsection