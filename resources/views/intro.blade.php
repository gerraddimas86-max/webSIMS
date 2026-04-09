<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community SIMS - Solidarity and Innovation Movement of Sriwijaya</title>
    @vite('resources/css/app.css')
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="font-sans min-h-screen flex items-center justify-center overflow-hidden relative bg-black">

    <!-- Background Image -->
    <div class="fixed inset-0 bg-cover bg-center opacity-0 animate-[bgFadeIn_1.5s_ease_0.3s_forwards]"
         style="background-image: url('{{ asset('images/unsri_bg.jpg') }}'); animation: bgFadeIn 1.5s ease 0.3s forwards;">
    </div>
    
    <!-- Overlay lebih soft -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

    <!-- Content - Hanya Quote -->
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
        
        <!-- Logo Dot Minimalis -->
        <div class="w-1.5 h-1.5 bg-blue-400/50 rounded-full mx-auto mb-16 opacity-0 animate-[dotPop_0.6s_ease_0.5s_forwards]"></div>

        <!-- Quote Container -->
        <div class="opacity-0 animate-[fadeIn_1.2s_ease_1.2s_forwards]">
            
            <!-- Opening Quote Icon -->
            <div class="text-7xl md:text-8xl text-blue-500/20 font-serif mb-6 opacity-0 animate-[fadeIn_0.8s_ease_1.6s_forwards]">"</div>
            
            <!-- Slogan Text dengan Typography Tebal & Berat -->
            <div class="relative">
                <!-- Teks utama dengan weight super bold -->
                <p class="text-3xl md:text-5xl lg:text-6xl font-black leading-tight md:leading-[1.3] tracking-wide">
                    <span class="bg-linear-to-r from-white via-blue-100 to-blue-300 bg-clip-text text-transparent drop-shadow-2xl">
                        Solidarity and Innovation<br>
                        Movement of Sriwijaya
                    </span>
                </p>
                
                <!-- Teks pendukung sebagai sub-quote -->
                <p class="text-md md:text-lg font-semibold text-white/60 tracking-wide mt-6 italic">
                    "Bergerak dalam Kebersamaan, Berinovasi untuk Perubahan"
                </p>
                
                <!-- Elegant Underline yang lebih tebal -->
                <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-linear-to-r from-transparent via-blue-400/70 to-transparent animate-[underlineGrow_1s_ease-out_2.5s_forwards]"></div>
            </div>
            
            <!-- Closing Quote Icon -->
            <div class="text-7xl md:text-8xl text-blue-500/20 font-serif mt-8 transform rotate-180 opacity-0 animate-[fadeIn_0.8s_ease_2s_forwards]">"</div>
        </div>

        <!-- Subtle Loading Indicator -->
        <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-2 opacity-0 animate-[fadeIn_0.6s_ease_2.5s_forwards]">
            <div class="flex gap-1.5">
                <div class="w-1.5 h-1.5 bg-blue-400/40 rounded-full animate-pulse" style="animation-delay: 0s"></div>
                <div class="w-1.5 h-1.5 bg-blue-400/40 rounded-full animate-pulse" style="animation-delay: 0.3s"></div>
                <div class="w-1.5 h-1.5 bg-blue-400/40 rounded-full animate-pulse" style="animation-delay: 0.6s"></div>
            </div>
            <p class="text-[10px] text-white/20 uppercase tracking-[0.2em]" id="progressText">loading</p>
        </div>
    </div>

    <style>
        /* Custom animations */
        @keyframes bgFadeIn {
            to { opacity: 1; }
        }
        
        @keyframes dotPop {
            0% { opacity: 0; transform: scale(0); }
            60% { opacity: 1; transform: scale(1.5); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        
        @keyframes underlineGrow {
            to { width: 180px; }
        }
        
        /* Page fade out */
        body.leaving {
            animation: pageOut 0.8s ease forwards;
        }
        
        @keyframes pageOut {
            to { opacity: 0; visibility: hidden; }
        }
        
        /* Efek tambahan untuk teks yang lebih tebal */
        .font-black {
            font-weight: 900 !important;
            letter-spacing: -0.02em;
        }
        
        /* Drop shadow untuk teks yang lebih dramatis */
        .drop-shadow-2xl {
            filter: drop-shadow(0 10px 8px rgb(0 0 0 / 0.2)) drop-shadow(0 4px 3px rgb(0 0 0 / 0.1));
        }
        
        /* Teks miring untuk kesan quote yang elegan */
        .italic {
            font-style: italic;
            font-weight: 600;
        }
    </style>

    <script>
        const messages = ['loading', 'almost there', 'welcome'];
        const textEl = document.getElementById('progressText');
        let i = 0;

        // Cycle through loading messages
        const msgInterval = setInterval(() => {
            i = (i + 1) % messages.length;
            textEl.textContent = messages[i];
        }, 1200);

        // Longer loading time - 5 seconds
        setTimeout(() => {
            clearInterval(msgInterval);
            textEl.textContent = 'enter';
            
            // Small delay before fade out
            setTimeout(() => {
                document.body.classList.add('leaving');
                setTimeout(() => {
                    window.location.href = "{{ route('posts.index') }}";
                }, 800);
            }, 300);
        }, 5000);
    </script>

</body>
</html>