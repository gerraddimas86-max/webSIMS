<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Komunitas Pelajar</title>
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
        .fade-up-2 { animation-delay: .12s; }
        .fade-up-3 { animation-delay: .19s; }
        .fade-up-4 { animation-delay: .26s; }
        .fade-up-5 { animation-delay: .33s; }

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
        }
        .input-field:focus {
            background: #fff;
            border-color: #1a1a1a;
            box-shadow: 0 0 0 3px rgba(26,26,26,.07);
        }
        .input-field::placeholder { color: #aaa; }

        .btn-login {
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
        .btn-login:hover { background: #333; transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }

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

        .stat-card {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: .75rem;
            padding: .85rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .stat-icon {
            width: 36px; height: 36px;
            background: rgba(255,255,255,.1);
            border-radius: .5rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .checkbox-label input[type="checkbox"]:checked {
            background-color: #1a1a1a;
            border-color: #1a1a1a;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative"
      style="background-image:url('{{ asset('images/unsri_bg.jpg') }}');background-size:cover;background-position:center;">

<!-- Full page overlay -->
<div style="position:absolute;inset:0;background:rgba(0,0,0,0.45);backdrop-filter:blur(2px);"></div>

<div class="w-11/12 max-w-5xl rounded-2xl shadow-2xl flex overflow-hidden relative z-10" style="min-height:580px;">

    <!-- Left Panel -->
    <div class="left-panel hidden md:flex w-5/12 p-10 flex-col justify-between relative z-10">
        <div class="dot-grid"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-1">
                <div style="width:8px;height:8px;border-radius:50%;background:#fff;opacity:.6;"></div>
                <span style="font-size:.75rem;color:rgba(255,255,255,.5);letter-spacing:.1em;text-transform:uppercase;">Komunitas Pelajar</span>
            </div>
        </div>

        <div class="relative z-10">
            <h1 style="font-family:'DM Serif Display',serif;font-size:2.6rem;color:#fff;line-height:1.2;margin-bottom:1rem;">
                Tempat<br>Berkumpulnya<br>Generasi<br>Berprestasi.
            </h1>
            <p style="color:rgba(255,255,255,.55);font-size:.875rem;line-height:1.6;margin-bottom:2rem;">
                Bergabunglah dengan ribuan pelajar dari seluruh Indonesia.
            </p>

            <div style="display:flex;flex-direction:column;gap:.65rem;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="rgba(255,255,255,.8)">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    <div>
                        <div style="color:#fff;font-weight:600;font-size:.95rem;">2.000+</div>
                        <div style="color:rgba(255,255,255,.45);font-size:.75rem;">Anggota Aktif</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="rgba(255,255,255,.8)">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <div style="color:#fff;font-weight:600;font-size:.95rem;">500+</div>
                        <div style="color:rgba(255,255,255,.45);font-size:.75rem;">Event Terselenggara</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10" style="color:rgba(255,255,255,.25);font-size:.7rem;">
            © 2025 Komunitas Pelajar
        </div>
    </div>

    <!-- Right Panel -->
    <div class="w-full md:w-7/12 p-10 flex flex-col justify-center" style="background:#fff;">

        <div class="fade-up fade-up-1" style="margin-bottom:2rem;">
            <h2 style="font-family:'DM Serif Display',serif;font-size:1.85rem;color:#1a1a1a;margin-bottom:.35rem;">Selamat Datang Kembali</h2>
            <p style="color:#888;font-size:.875rem;">Silakan masuk untuk melanjutkan</p>
        </div>

        @if($errors->any())
            <div class="fade-up" style="margin-bottom:1.25rem;background:#fef2f2;border:1px solid #fecaca;border-radius:.75rem;padding:.9rem 1rem;display:flex;gap:.65rem;align-items:flex-start;">
                <svg style="flex-shrink:0;margin-top:1px;" width="16" height="16" viewBox="0 0 20 20" fill="#ef4444">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p style="font-size:.85rem;color:#dc2626;">{{ $errors->first() }}</p>
            </div>
        @endif

        @if(session('status'))
            <div class="fade-up" style="margin-bottom:1.25rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.75rem;padding:.9rem 1rem;">
                <p style="font-size:.85rem;color:#16a34a;">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:1.1rem;">
            @csrf

            <!-- Email -->
            <div class="fade-up fade-up-2">
                <label style="display:block;font-size:.8rem;font-weight:500;color:#555;margin-bottom:.5rem;letter-spacing:.02em;">EMAIL</label>
                <div style="position:relative;">
                    <div style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);pointer-events:none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="input-field @error('email') !border-red-400 @enderror"
                        placeholder="nama@email.com" required autofocus>
                </div>
                @error('email')
                    <p style="margin-top:.35rem;font-size:.75rem;color:#ef4444;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="fade-up fade-up-3" x-data="{ show: false }">
                <label style="display:block;font-size:.8rem;font-weight:500;color:#555;margin-bottom:.5rem;letter-spacing:.02em;">PASSWORD</label>
                <div style="position:relative;">
                    <div style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);pointer-events:none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input :type="show ? 'text' : 'password'" name="password"
                        class="input-field @error('password') !border-red-400 @enderror"
                        placeholder="••••••••" required>
                    <button type="button" @click="show = !show"
                        style="position:absolute;right:.85rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:#aaa;">
                        <svg x-show="!show" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="show" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p style="margin-top:.35rem;font-size:.75rem;color:#ef4444;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember & Forgot -->
            <div class="fade-up fade-up-4" style="display:flex;align-items:center;justify-content:space-between;">
                <label class="checkbox-label" style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                    <input type="checkbox" name="remember"
                        style="width:15px;height:15px;border-radius:.25rem;border:1.5px solid #d1d5db;accent-color:#1a1a1a;cursor:pointer;">
                    <span style="font-size:.85rem;color:#666;">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        style="font-size:.85rem;color:#1a1a1a;text-decoration:underline;text-underline-offset:3px;">
                        Lupa password?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <div class="fade-up fade-up-5">
                <button type="submit" class="btn-login">MASUK</button>
            </div>

            <p class="fade-up" style="text-align:center;font-size:.85rem;color:#888;margin-top:.5rem;">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color:#1a1a1a;font-weight:600;text-decoration:underline;text-underline-offset:3px;">
                    Daftar sekarang
                </a>
            </p>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>