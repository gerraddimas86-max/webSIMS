<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Community SIMS</title>
    @vite('resources/css/app.css')

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Bersihin mata bawaan browser - clean version */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear,
        input[type="password"]::-webkit-reveal-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none !important;
        }

        /* FIX: Override autofill background color */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #1c2333 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            background-color: #1c2333 !important;
            background-image: none !important;
    }
    </style>

</head>

<body class="font-sans min-h-screen flex items-center justify-center px-4 py-8 bg-cover bg-center relative"
      style="background-image: url('{{ asset('images/unsri_bg.jpg') }}')">

    <div class="fixed inset-0 bg-[#05080f]/75 backdrop-blur"></div>

    <div class="relative z-10 w-full max-w-6xl flex rounded-2xl overflow-hidden shadow-[0_30px_70px_rgba(0,0,0,0.65)] border border-white/10">

        <!-- LEFT PANEL -->
        <div class="hidden md:flex w-[42%] flex-col justify-between p-11 bg-[#0d1117] relative overflow-hidden">

            <div class="absolute inset-0 bg-cover bg-center opacity-10"
                 style="background-image: url('{{ asset('images/unsri_bg.jpg') }}')"></div>

            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-blue-500/10 blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full border border-blue-400/10"></div>
            <div class="absolute -bottom-10 -right-10 w-48 h-48 rounded-full border border-blue-400/5"></div>

            <div class="absolute top-10 right-10 w-28 h-28 bg-[radial-gradient(circle,_rgba(96,165,250,0.18)_1px,_transparent_1px)] bg-[length:12px_12px]"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                    <span class="text-[10px] tracking-widest text-white/30 uppercase">Community SIMS</span>
                </div>

                <h1 class="font-serif text-[2.3rem] text-white leading-tight mb-3">
                    Bergabung &<br>Wujudkan<br>
                    <span class="text-blue-400 italic">Potensimu.</span>
                </h1>

                <p class="text-white/50 text-sm leading-relaxed mb-8">
                    Solidarity and Innovation Movement of Sriwijaya — jaringan pelajar terbaik untuk tumbuh bersama.
                </p>

                <div class="space-y-3 text-sm text-white/60">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 flex items-center justify-center rounded bg-blue-500/10 border border-blue-400/20">
                            <i class="fa-solid fa-check text-blue-400 text-xs"></i>
                        </div>
                        Networking dengan 2.000+ pelajar
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 flex items-center justify-center rounded bg-blue-500/10 border border-blue-400/20">
                            <i class="fa-solid fa-check text-blue-400 text-xs"></i>
                        </div>
                        Akses ke event eksklusif
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 flex items-center justify-center rounded bg-blue-500/10 border border-blue-400/20">
                            <i class="fa-solid fa-check text-blue-400 text-xs"></i>
                        </div>
                        Diskusi dan sharing knowledge
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-xs text-white/30">© 2025 Community SIMS</div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="flex-1 flex flex-col justify-center px-12 py-12 bg-[#161b24] relative overflow-hidden">

            <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-blue-500/10 blur-3xl"></div>
            <div class="absolute left-0 top-1/5 h-3/5 w-[2px] bg-gradient-to-b from-transparent via-blue-500/40 to-transparent"></div>

            <div class="relative z-10">
                <h2 class="font-serif text-2xl text-white mb-1">Buat Akun Baru</h2>
                <p class="text-white/50 text-sm mb-6">Isi data dengan benar untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="relative z-10 mb-5 flex gap-2 p-3 rounded-xl bg-red-500/10 border border-red-400/30 text-red-300 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="relative z-10 space-y-4" x-data="registerForm()">
                @csrf

                @php
                    $inputClass = "w-full pl-10 pr-10 py-3 bg-[#1c2333] border border-white/10 rounded-xl text-white placeholder-white/30 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none";
                @endphp

                <!-- Nama -->
                <div>
                    <label class="text-xs tracking-widest text-white/30 uppercase">Nama Lengkap</label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-white/30"></i>
                        <input type="text" name="name" value="{{ old('name') }}" class="{{ $inputClass }}" placeholder="Masukkan nama lengkap">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="text-xs tracking-widest text-white/30 uppercase">Email</label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-white/30"></i>
                        <input type="email" name="email" value="{{ old('email') }}" class="{{ $inputClass }}" placeholder="nama@email.com">
                    </div>
                </div>

                <!-- Password dengan icon mata putih -->
                <div x-data="{show:false}">
                    <label class="text-xs tracking-widest text-white/30 uppercase">Password</label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-white/30"></i>
                        <input :type="show?'text':'password'" x-model="password" @input="checkStrength" name="password" class="{{ $inputClass }}" placeholder="Minimal 8 karakter">
                        <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors">
                            <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </button>
                    </div>

                    <div x-show="password.length>0" class="mt-2">
                        <div class="flex gap-1">
                            <template x-for="i in 4">
                                <div class="h-[3px] flex-1 rounded"
                                     :class="strength>=i ? (strength===1?'bg-red-400':strength===2?'bg-yellow-400':strength===3?'bg-cyan-400':'bg-green-400') : 'bg-[#2e3a4e]'">
                                </div>
                            </template>
                        </div>
                        <p class="text-xs mt-1"
                           :class="strength===1?'text-red-400':strength===2?'text-yellow-400':strength===3?'text-cyan-400':'text-green-400'"
                           x-text="strengthText"></p>
                    </div>
                </div>

                <!-- Confirm Password dengan icon mata putih -->
                <div x-data="{show:false}">
                    <label class="text-xs tracking-widest text-white/30 uppercase">Konfirmasi Password</label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-white/30"></i>
                        <input :type="show?'text':'password'" x-model="passwordConfirmation" @input="checkMatch" name="password_confirmation" class="{{ $inputClass }}" placeholder="Ketik ulang password">
                        <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors">
                            <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </button>
                    </div>

                    <p x-show="passwordConfirmation.length>0" class="text-xs mt-1"
                       :class="passwordMatch ? 'text-green-400':'text-red-400'"
                       x-text="passwordMatch ? '✓ Password cocok':'✗ Password tidak cocok'"></p>
                </div>

                <!-- Terms -->
                <div class="flex items-start gap-2 text-sm text-white/50">
                    <input type="checkbox" name="terms" value="1" required class="mt-1 accent-blue-500">
                    <span>
                        Saya setuju dengan
                        <a href="#" class="text-blue-400 underline">Syarat & Ketentuan</a>
                        dan
                        <a href="#" class="text-blue-400 underline">Kebijakan Privasi</a>
                    </span>
                </div>

                <!-- Button -->
                <button class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold tracking-wide shadow-lg shadow-blue-500/30 transition">
                    DAFTAR SEKARANG
                </button>

                <p class="text-center text-sm text-white/50">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-blue-400 underline">Login di sini</a>
                </p>

            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function registerForm() {
            return {
                password: '',
                passwordConfirmation: '',
                strength: 0,
                strengthText: '',
                passwordMatch: false,

                checkStrength() {
                    let s = 0;
                    if (this.password.length >= 8) s++;
                    if (this.password.length >= 12) s++;
                    if (/[A-Z]/.test(this.password)) s++;
                    if (/[0-9]/.test(this.password)) s++;
                    if (/[^A-Za-z0-9]/.test(this.password)) s++;

                    this.strength = Math.min(4, s);
                    this.strengthText = ['Sangat Lemah','Lemah','Sedang','Kuat','Sangat Kuat'][this.strength];
                    this.checkMatch();
                },

                checkMatch() {
                    this.passwordMatch = this.password.length>0 && this.password===this.passwordConfirmation;
                }
            }
        }
    </script>

</body>
</html>