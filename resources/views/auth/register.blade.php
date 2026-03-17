<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Komunitas Pelajar</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'DM Sans', sans-serif; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.55s cubic-bezier(.22,.68,0,1.2) both; }
        .fade-up-1 { animation-delay: .05s; }
        .fade-up-2 { animation-delay: .10s; }
        .fade-up-3 { animation-delay: .15s; }
        .fade-up-4 { animation-delay: .20s; }
        .fade-up-5 { animation-delay: .25s; }
        .fade-up-6 { animation-delay: .30s; }
        .fade-up-7 { animation-delay: .35s; }

        .input-field {
            width: 100%;
            padding: .75rem 1rem .75rem 2.75rem;
            background: #f8f8f7;
            border: 1.5px solid #e8e6e1;
            border-radius: .75rem;
            font-size: .9rem;
            color: #1a1a1a;
            transition: border-color .2s, background .2s, box-shadow .2s;
            outline: none;
            font-family: 'DM Sans', sans-serif;
        }
        .input-field:focus {
            background: #fff;
            border-color: #1a1a1a;
            box-shadow: 0 0 0 3px rgba(26,26,26,.07);
        }
        .input-field::placeholder { color: #aaa; }

        .btn-register {
            width: 100%;
            padding: .85rem;
            background: #1a1a1a;
            color: #fff;
            border: none;
            border-radius: .75rem;
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            font-weight: 600;
            letter-spacing: .04em;
            cursor: pointer;
            transition: background .2s, transform .15s;
        }
        .btn-register:hover { background: #333; transform: translateY(-1px); }
        .btn-register:active { transform: translateY(0); }

        .left-panel {
            background: #1a1a1a;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("{{ asset('images/unsri_bg.jpg') }}");
            background-size: cover;
            background-position: center;
            opacity: .35;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -80px; right: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,.08);
        }
        .dot-grid {
            position: absolute;
            top: 40px; right: 40px;
            width: 120px; height: 120px;
            background-image: radial-gradient(circle, rgba(255,255,255,.15) 1px, transparent 1px);
            background-size: 12px 12px;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .benefit-check {
            width: 24px; height: 24px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: .375rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* Strength meter */
        .strength-bar {
            height: 3px;
            flex: 1;
            border-radius: 2px;
            background: #e8e6e1;
            transition: background .3s;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center py-8 relative"
      style="background-image:url('{{ asset('images/unsri_bg.jpg') }}');background-size:cover;background-position:center;">

<!-- Full page overlay -->
<div style="position:absolute;inset:0;background:rgba(0,0,0,0.45);backdrop-filter:blur(2px);"></div>

<div class="w-11/12 max-w-5xl rounded-2xl shadow-2xl flex overflow-hidden relative z-10">

    <!-- Left Panel -->
    <div class="left-panel hidden md:flex w-5/12 p-10 flex-col justify-between relative z-10">
        <div class="dot-grid"></div>

        <div class="relative z-10">
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem;">
                <div style="width:8px;height:8px;border-radius:50%;background:#fff;opacity:.6;"></div>
                <span style="font-size:.75rem;color:rgba(255,255,255,.5);letter-spacing:.1em;text-transform:uppercase;">Komunitas Pelajar</span>
            </div>
        </div>

        <div class="relative z-10">
            <h1 style="font-family:'DM Serif Display',serif;font-size:2.4rem;color:#fff;line-height:1.2;margin-bottom:1rem;">
                Bergabung &<br>Wujudkan<br>Potensimu.
            </h1>
            <p style="color:rgba(255,255,255,.55);font-size:.875rem;line-height:1.6;margin-bottom:2rem;">
                Dapatkan akses ke berbagai event, diskusi, dan jaringan pelajar dari seluruh Indonesia.
            </p>

            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div class="benefit-item">
                    <div class="benefit-check">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 6l3 3 5-5" stroke="rgba(255,255,255,.8)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span style="color:rgba(255,255,255,.7);font-size:.875rem;">Networking dengan 2.000+ pelajar</span>
                </div>
                <div class="benefit-item">
                    <div class="benefit-check">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 6l3 3 5-5" stroke="rgba(255,255,255,.8)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span style="color:rgba(255,255,255,.7);font-size:.875rem;">Akses ke event eksklusif</span>
                </div>
                <div class="benefit-item">
                    <div class="benefit-check">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 6l3 3 5-5" stroke="rgba(255,255,255,.8)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span style="color:rgba(255,255,255,.7);font-size:.875rem;">Diskusi dan sharing knowledge</span>
                </div>
            </div>
        </div>

        <div class="relative z-10" style="color:rgba(255,255,255,.25);font-size:.7rem;">
            © 2025 Komunitas Pelajar
        </div>
    </div>

    <!-- Right Panel -->
    <div class="w-full md:w-7/12 p-10 flex flex-col justify-center" style="background:#fff;">

        <div class="fade-up fade-up-1" style="margin-bottom:1.75rem;">
            <h2 style="font-family:'DM Serif Display',serif;font-size:1.85rem;color:#1a1a1a;margin-bottom:.35rem;">Buat Akun Baru</h2>
            <p style="color:#888;font-size:.875rem;">Isi data dengan benar untuk melanjutkan</p>
        </div>

        @if($errors->any())
            <div class="fade-up" style="margin-bottom:1.25rem;background:#fef2f2;border:1px solid #fecaca;border-radius:.75rem;padding:.9rem 1rem;display:flex;gap:.65rem;align-items:flex-start;">
                <svg style="flex-shrink:0;margin-top:2px;" width="15" height="15" viewBox="0 0 20 20" fill="#ef4444">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <ul style="font-size:.82rem;color:#dc2626;list-style:disc inside;display:flex;flex-direction:column;gap:.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:1rem;" x-data="registerForm()">
            @csrf

            <!-- Name -->
            <div class="fade-up fade-up-2">
                <label style="display:block;font-size:.78rem;font-weight:500;color:#555;margin-bottom:.45rem;letter-spacing:.02em;">NAMA LENGKAP</label>
                <div style="position:relative;">
                    <div style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);pointer-events:none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="input-field @error('name') !border-red-400 @enderror"
                        placeholder="Masukkan nama lengkap" required autofocus>
                </div>
            </div>

            <!-- Email -->
            <div class="fade-up fade-up-3">
                <label style="display:block;font-size:.78rem;font-weight:500;color:#555;margin-bottom:.45rem;letter-spacing:.02em;">EMAIL</label>
                <div style="position:relative;">
                    <div style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);pointer-events:none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="input-field @error('email') !border-red-400 @enderror"
                        placeholder="nama@email.com" required>
                </div>
            </div>

            <!-- Password -->
            <div class="fade-up fade-up-4" x-data="{ show: false }">
                <label style="display:block;font-size:.78rem;font-weight:500;color:#555;margin-bottom:.45rem;letter-spacing:.02em;">PASSWORD</label>
                <div style="position:relative;">
                    <div style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);pointer-events:none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input :type="show ? 'text' : 'password'" name="password"
                        x-model="password" @input="checkStrength"
                        class="input-field"
                        placeholder="Minimal 8 karakter" required>
                    <button type="button" @click="show = !show"
                        style="position:absolute;right:.85rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:#aaa;">
                        <svg x-show="!show" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="show" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>

                <!-- Strength Meter -->
                <div x-show="password.length > 0" style="margin-top:.6rem;">
                    <div style="display:flex;gap:4px;margin-bottom:.3rem;">
                        <template x-for="i in 4" :key="i">
                            <div class="strength-bar"
                                :style="{
                                    background: strength >= i
                                        ? (strength === 1 ? '#ef4444' : strength === 2 ? '#f59e0b' : strength === 3 ? '#3b82f6' : '#22c55e')
                                        : '#e8e6e1'
                                }"></div>
                        </template>
                    </div>
                    <p style="font-size:.75rem;" :style="{color: strength===1?'#ef4444':strength===2?'#f59e0b':strength===3?'#3b82f6':'#22c55e'}" x-text="strengthText"></p>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="fade-up fade-up-5" x-data="{ show: false }">
                <label style="display:block;font-size:.78rem;font-weight:500;color:#555;margin-bottom:.45rem;letter-spacing:.02em;">KONFIRMASI PASSWORD</label>
                <div style="position:relative;">
                    <div style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);pointer-events:none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input :type="show ? 'text' : 'password'" name="password_confirmation"
                        x-model="passwordConfirmation" @input="checkMatch"
                        class="input-field"
                        placeholder="Ketik ulang password" required>
                    <button type="button" @click="show = !show"
                        style="position:absolute;right:.85rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:#aaa;">
                        <svg x-show="!show" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="show" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                <div x-show="passwordConfirmation.length > 0" style="margin-top:.35rem;font-size:.75rem;"
                    :style="{color: passwordMatch ? '#22c55e' : '#ef4444'}"
                    x-text="passwordMatch ? '✓ Password cocok' : '✗ Password tidak cocok'">
                </div>
            </div>

            <!-- Terms -->
            <div class="fade-up fade-up-6" style="display:flex;align-items:flex-start;gap:.6rem;">
                <input type="checkbox" name="terms" id="terms" required
                    style="margin-top:2px;width:15px;height:15px;border-radius:.25rem;accent-color:#1a1a1a;cursor:pointer;flex-shrink:0;">
                <label for="terms" style="font-size:.83rem;color:#666;line-height:1.5;cursor:pointer;">
                    Saya setuju dengan
                    <a href="#" style="color:#1a1a1a;text-decoration:underline;text-underline-offset:2px;">Syarat &amp; Ketentuan</a>
                    dan
                    <a href="#" style="color:#1a1a1a;text-decoration:underline;text-underline-offset:2px;">Kebijakan Privasi</a>
                </label>
            </div>

            <!-- Submit -->
            <div class="fade-up fade-up-7">
                <button type="submit" class="btn-register">DAFTAR SEKARANG</button>
            </div>

            <p class="fade-up" style="text-align:center;font-size:.85rem;color:#888;margin-top:.25rem;">
                Sudah punya akun?
                <a href="{{ route('login') }}" style="color:#1a1a1a;font-weight:600;text-decoration:underline;text-underline-offset:3px;">
                    Login di sini
                </a>
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
            },
            checkMatch() {
                this.passwordMatch = this.password === this.passwordConfirmation && this.password.length > 0;
            }
        }
    }
</script>
</body>
</html>