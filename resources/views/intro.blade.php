<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunitas Pelajar</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #0e0e0e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Background image */
        .bg-image {
            position: fixed;
            inset: 0;
            background-image: url('{{ asset("images/unsri_bg.jpg") }}');
            background-size: cover;
            background-position: center;
            opacity: 0;
            animation: bgFadeIn 1.2s ease 0.2s forwards;
        }
        .bg-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.65) 100%);
        }

        /* Content */
        .content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
        }

        /* Logo dot */
        .logo-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #fff;
            margin: 0 auto 2rem;
            opacity: 0;
            animation: dotPop 0.4s cubic-bezier(.34,1.56,.64,1) 0.6s forwards;
        }

        /* Title */
        .title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            color: #fff;
            line-height: 1.1;
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.7s cubic-bezier(.22,.68,0,1.2) 0.9s forwards;
            margin-bottom: 1rem;
        }
        .title span { color: rgba(255,255,255,0.45); }

        /* Tagline */
        .tagline {
            font-size: 1rem;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            opacity: 0;
            animation: slideUp 0.6s ease 1.2s forwards;
            margin-bottom: 3.5rem;
        }

        /* Progress bar */
        .progress-wrap {
            width: 200px;
            margin: 0 auto;
            opacity: 0;
            animation: fadeIn 0.4s ease 1.5s forwards;
        }
        .progress-track {
            height: 2px;
            background: rgba(255,255,255,0.12);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.75rem;
        }
        .progress-bar {
            height: 100%;
            width: 0%;
            background: #fff;
            border-radius: 2px;
            animation: progressFill 2s cubic-bezier(.4,0,.2,1) 1.6s forwards;
        }
        .progress-text {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.35);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* Stats row */
        .stats {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            margin-top: 3.5rem;
            opacity: 0;
            animation: fadeIn 0.6s ease 1.8s forwards;
        }
        .stat { text-align: center; }
        .stat-num {
            font-size: 1.4rem;
            font-weight: 600;
            color: #fff;
            line-height: 1;
            margin-bottom: 0.2rem;
        }
        .stat-label {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .stat-divider {
            width: 1px;
            background: rgba(255,255,255,0.1);
            align-self: stretch;
        }

        /* Keyframes */
        @keyframes bgFadeIn {
            to { opacity: 1; }
        }
        @keyframes dotPop {
            from { opacity: 0; transform: scale(0); }
            to   { opacity: 1; transform: scale(1); }
        }
        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        @keyframes progressFill {
            to { width: 100%; }
        }

        /* Fade out whole page before redirect */
        body.leaving {
            animation: pageOut 0.5s ease forwards;
        }
        @keyframes pageOut {
            to { opacity: 0; }
        }
    </style>
</head>
<body>

    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="content">
        <div class="logo-dot"></div>

        <h1 class="title">
            Komunitas<br><span>Pelajar</span>
        </h1>

        <p class="tagline">Tempat Berkumpulnya Generasi Berprestasi</p>

        <div class="progress-wrap">
            <div class="progress-track">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            <p class="progress-text" id="progressText">Memuat...</p>
        </div>

        <div class="stats">
            <div class="stat">
                <div class="stat-num">2.000+</div>
                <div class="stat-label">Anggota</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <div class="stat-num">500+</div>
                <div class="stat-label">Event</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <div class="stat-num">10rb+</div>
                <div class="stat-label">Postingan</div>
            </div>
        </div>
    </div>

    <script>
        const messages = ['Memuat...', 'Menyiapkan feed...', 'Hampir selesai...'];
        const textEl = document.getElementById('progressText');
        let i = 0;

        // Cycle through loading messages
        const msgInterval = setInterval(() => {
            i = (i + 1) % messages.length;
            textEl.textContent = messages[i];
        }, 800);

        // After progress animation completes (~3.6s total), redirect
        setTimeout(() => {
            clearInterval(msgInterval);
            textEl.textContent = 'Siap!';
            document.body.classList.add('leaving');
            setTimeout(() => {
                window.location.href = "{{ route('posts.index') }}";
            }, 500);
        }, 3600);
    </script>
</body>
</html>