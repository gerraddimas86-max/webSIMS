<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Community SIMS</title>
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
        
        /* Hide success message after 5 seconds */
        .success-alert {
            animation: fadeOut 0.5s ease-out 4.5s forwards;
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
            }
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
                    Selamat<br>Datang<br>
                    <span class="text-blue-400 italic">Kembali.</span>
                </h1>

                <p class="text-white/50 text-sm leading-relaxed mb-8">
                    Solidarity and Innovation Movement of Sriwijaya — tempat di mana kolaborasi dan inovasi bertemu.
                </p>

                <div class="space-y-3 text-sm text-white/60">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 flex items-center justify-center rounded bg-blue-500/10 border border-blue-400/20">
                            <i class="fa-solid fa-check text-blue-400 text-xs"></i>
                        </div>
                        Akses penuh ke dashboard member
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 flex items-center justify-center rounded bg-blue-500/10 border border-blue-400/20">
                            <i class="fa-solid fa-check text-blue-400 text-xs"></i>
                        </div>
                        Ikuti event dan workshop
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 flex items-center justify-center rounded bg-blue-500/10 border border-blue-400/20">
                            <i class="fa-solid fa-check text-blue-400 text-xs"></i>
                        </div>
                        Terhubung dengan komunitas
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
                <h2 class="font-serif text-2xl text-white mb-1">Masuk ke Akun</h2>
                <p class="text-white/50 text-sm mb-6">Masukkan kredensial Anda untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="relative z-10 mb-5 flex gap-2 p-3 rounded-xl bg-red-500/10 border border-red-400/30 text-red-300 text-sm">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="relative z-10 mb-5 flex gap-2 p-3 rounded-xl bg-green-500/10 border border-green-400/30 text-green-300 text-sm success-alert">
                    <i class="fa-solid fa-check-circle mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="relative z-10 mb-5 flex gap-2 p-3 rounded-xl bg-red-500/10 border border-red-400/30 text-red-300 text-sm success-alert">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="relative z-10 space-y-4">
                @csrf

                @php
                    $inputClass = "w-full pl-10 pr-4 py-3 bg-[#1c2333] border border-white/10 rounded-xl text-white placeholder-white/30 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all";
                @endphp

                <!-- Email -->
                <div>
                    <label class="text-xs tracking-widest text-white/30 uppercase">Email</label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-white/30"></i>
                        <input type="email" name="email" value="{{ old('email') }}" class="{{ $inputClass }}" placeholder="nama@email.com" autofocus required>
                    </div>
                </div>

                <!-- Password dengan icon mata -->
                <div x-data="{show:false}">
                    <label class="text-xs tracking-widest text-white/30 uppercase">Password</label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-white/30"></i>
                        <input :type="show?'text':'password'" name="password" class="{{ $inputClass }}" placeholder="Masukkan password" required>
                        <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors">
                            <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-white/50 cursor-pointer">
                        <input type="checkbox" name="remember" class="accent-blue-500 rounded w-4 h-4">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <!-- Button -->
                <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold tracking-wide shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:scale-[1.02]">
                    MASUK SEKARANG
                </button>

                <p class="text-center text-sm text-white/50">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 underline transition-colors">
                        Daftar di sini
                    </a>
                </p>

            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    @if(session('success') || session('error'))
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.success-alert');
            alerts.forEach(alert => {
                if(alert) alert.style.display = 'none';
            });
        }, 5000);
    </script>
    @endif

</body>
</html>