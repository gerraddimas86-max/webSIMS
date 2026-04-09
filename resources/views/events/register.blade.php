@extends('layouts.app')

@section('content')

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeUp {
        animation: fadeUp 0.5s ease forwards;
    }
</style>

<div class="w-full max-w-2xl mx-auto px-4 py-8 font-['DM_Sans'] text-[#f0f4f8]">

    {{-- Back Button --}}
    <div class="mb-6 animate-fadeUp opacity-0" style="animation-delay: 0.05s">
        <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center gap-2 text-white/45 hover:text-[#f0f4f8] transition-colors">
            <i class="fa-solid fa-circle-arrow-left text-sm"></i>
            <span class="text-sm">Kembali ke Detail Event</span>
        </a>
    </div>

    {{-- Registration Form --}}
    <div class="bg-[#161b24] border border-white/7 rounded-2xl overflow-hidden animate-fadeUp opacity-0" style="animation-delay: 0.1s">
        <div class="p-6 md:p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-ticket text-2xl text-primary-400"></i>
                </div>
                <h2 class="font-['DM_Serif_Display'] text-2xl text-[#f0f4f8] mb-2">Pendaftaran Event</h2>
                <p class="text-white/45 text-sm">Isi form di bawah untuk mendaftar event</p>
            </div>
            
            {{-- Event Info --}}
            <div class="bg-[#1c2333] border border-white/7 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fa-solid fa-calendar-days text-primary-400"></i>
                    <div>
                        <div class="text-sm font-semibold text-[#f0f4f8]">{{ $event->name }}</div>
                        <div class="text-xs text-white/45">
                            {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-clock text-white/30"></i>
                    <div class="text-xs text-white/45">
                        Pendaftaran ditutup pada H-1 event
                    </div>
                </div>
            </div>
            
            {{-- Error Messages --}}
            @if($errors->any())
                <div class="mb-6 bg-[#f87171]/10 border border-[#f87171]/20 rounded-xl px-4 py-3 text-sm text-[#f87171]">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- Form --}}
            <form method="POST" action="{{ route('events.register.store', $event->id) }}">
                @csrf
                
                {{-- Nama Lengkap --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-white/45 uppercase tracking-wide mb-2">
                        <i class="fa-solid fa-user mr-1.5"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required
                           class="w-full px-4 py-2.5 bg-[#1c2333] border border-white/7 rounded-xl text-sm text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all {{ $errors->has('full_name') ? 'border-[#f87171]' : '' }}">
                    @error('full_name')
                        <div class="text-xs text-[#f87171] mt-1.5">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- Email --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-white/45 uppercase tracking-wide mb-2">
                        <i class="fa-solid fa-envelope mr-1.5"></i>
                        Alamat Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="w-full px-4 py-2.5 bg-[#1c2333] border border-white/7 rounded-xl text-sm text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all {{ $errors->has('email') ? 'border-[#f87171]' : '' }}">
                    @error('email')
                        <div class="text-xs text-[#f87171] mt-1.5">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- No. Telepon --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-white/45 uppercase tracking-wide mb-2">
                        <i class="fa-solid fa-phone mr-1.5"></i>
                        Nomor Telepon
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-2.5 bg-[#1c2333] border border-white/7 rounded-xl text-sm text-[#f0f4f8] placeholder-white/20 outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all {{ $errors->has('phone') ? 'border-[#f87171]' : '' }}"
                           placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <div class="text-xs text-[#f87171] mt-1.5">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- Fakultas (Dropdown) --}}
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-white/45 uppercase tracking-wide mb-2">
                        <i class="fa-solid fa-building-columns mr-1.5"></i>
                        Fakultas
                    </label>
                    <select name="faculty" required
                            class="w-full px-4 py-2.5 bg-[#1c2333] border border-white/7 rounded-xl text-sm text-[#f0f4f8] outline-none focus:border-primary-500 focus:ring-2 focus:ring-blue-500/10 transition-all {{ $errors->has('faculty') ? 'border-[#f87171]' : '' }}">
                        <option value="" disabled {{ old('faculty') ? '' : 'selected' }}>Pilih Fakultas</option>
                        <option value="Fakultas Ilmu Komputer" {{ old('faculty') == 'Fakultas Ilmu Komputer' ? 'selected' : '' }}>Fakultas Ilmu Komputer</option>
                        <option value="Fakultas Teknik" {{ old('faculty') == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                        <option value="Fakultas Kedokteran" {{ old('faculty') == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                        <option value="Fakultas Ekonomi" {{ old('faculty') == 'Fakultas Ekonomi' ? 'selected' : '' }}>Fakultas Ekonomi</option>
                        <option value="Fakultas Hukum" {{ old('faculty') == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                        <option value="Fakultas Ilmu Sosial dan Ilmu Politik" {{ old('faculty') == 'Fakultas Ilmu Sosial dan Ilmu Politik' ? 'selected' : '' }}>Fakultas Ilmu Sosial dan Ilmu Politik</option>
                        <option value="Fakultas Pertanian" {{ old('faculty') == 'Fakultas Pertanian' ? 'selected' : '' }}>Fakultas Pertanian</option>
                        <option value="Fakultas Keguruan dan Ilmu Pendidikan" {{ old('faculty') == 'Fakultas Keguruan dan Ilmu Pendidikan' ? 'selected' : '' }}>Fakultas Keguruan dan Ilmu Pendidikan</option>
                        <option value="Fakultas Matematika dan Ilmu Pengetahuan Alam" {{ old('faculty') == 'Fakultas Matematika dan Ilmu Pengetahuan Alam' ? 'selected' : '' }}>Fakultas Matematika dan Ilmu Pengetahuan Alam</option>
                        <option value="Fakultas Seni dan Desain" {{ old('faculty') == 'Fakultas Seni dan Desain' ? 'selected' : '' }}>Fakultas Seni dan Desain</option>
                        <option value="Fakultas Psikologi" {{ old('faculty') == 'Fakultas Psikologi' ? 'selected' : '' }}>Fakultas Psikologi</option>
                        <option value="Fakultas Keperawatan" {{ old('faculty') == 'Fakultas Keperawatan' ? 'selected' : '' }}>Fakultas Keperawatan</option>
                        <option value="Fakultas Farmasi" {{ old('faculty') == 'Fakultas Farmasi' ? 'selected' : '' }}>Fakultas Farmasi</option>
                        <option value="Fakultas Kedokteran Gigi" {{ old('faculty') == 'Fakultas Kedokteran Gigi' ? 'selected' : '' }}>Fakultas Kedokteran Gigi</option>
                        <option value="Fakultas Peternakan" {{ old('faculty') == 'Fakultas Peternakan' ? 'selected' : '' }}>Fakultas Peternakan</option>
                        <option value="Lainnya" {{ old('faculty') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('faculty')
                        <div class="text-xs text-[#f87171] mt-1.5">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-white/7">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary-600 text-white rounded-xl font-semibold text-sm hover:bg-primary-500 hover:-translate-y-px transition-all duration-200 shadow-lg shadow-blue-500/20">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        Konfirmasi Pendaftaran
                    </button>

                    <a href="{{ route('events.show', $event->id) }}" 
                       class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-transparent border border-white/13 text-white/45 rounded-xl font-semibold text-sm hover:border-white/20 hover:text-[#f0f4f8] transition-all duration-200">
                        <i class="fa-solid fa-circle-xmark text-xs"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection