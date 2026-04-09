<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Community SIMS</title>

    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Only keep essential utilities that Tailwind doesn't provide */
        @layer utilities {
            .bg-gradient-nav {
                background: linear-gradient(90deg, transparent, rgba(59,130,246,.35), transparent);
            }
        }
    </style>
</head>

<body class="font-['DM_Sans'] min-h-screen bg-[#080c12]">

    {{-- Background layer --}}
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/unsri_bg.jpg') }}" alt="" 
             class="w-full h-full object-cover filter blur-[6px] brightness-[.45] saturate-[.7] scale-105">
        <div class="absolute inset-0 bg-[#080c12]/55"></div>
    </div>

    {{-- ── Navbar ──────────────────────────────────────────── --}}
    <nav class="sticky top-0 z-50 bg-[#0d1117]/82 backdrop-blur-xl border-b border-white/7 py-3 px-5 flex items-center justify-between">
        
        {{-- Blue glow line at bottom --}}
        <div class="absolute -bottom-px left-[10%] right-[10%] h-px bg-gradient-nav"></div>

        {{-- Logo with perfect width alignment (responsive) --}}
        <a href="{{ route('posts.index') }}" class="flex items-center gap-2.5 no-underline group">
            <img src="{{ asset('images/Logo_unsri.jpg') }}" alt="SIMS Logo" 
                class="w-10 h-10 rounded-lg object-contain border border-white/7 transition-transform group-hover:scale-105 duration-200 shrink-0">
            
            <div class="leading-tight">
                <h1 class="font-['DM_Sans'] font-bold text-[#f0f4f8] m-0 whitespace-nowrap"
                    style="font-size: 20px; letter-spacing: 2.2px; text-shadow: 2px 2px 6px rgba(0,0,0,0.5);">
                    COMMUNITY SIMS
                </h1>
                <p class="text-white/50 m-0 mt-0.5 whitespace-nowrap"
                    style="font-size: 7px; letter-spacing: 0.6px; text-shadow: 1px 1px 4px rgba(0,0,0,0.5);">
                    SOLIDARITY AND INNOVATION MOVEMENT OF SRIWIJAYA
                </p>
            </div>
        </a>

        {{-- Desktop center icons --}}
        <div class="absolute left-1/2 -translate-x-1/2 hidden md:flex items-center gap-1">
            <a href="{{ route('posts.index') }}"
               class="relative flex items-center justify-center w-10 h-10 rounded-lg text-white/45 border border-transparent hover:text-primary-400 hover:bg-blue-500/10 hover:border-blue-500/18 hover:-translate-y-0.5 transition-all duration-200 {{ request()->routeIs('posts.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/15' : '' }} group"
               data-tip="Beranda">
                <i data-lucide="home" class="w-4.5 h-4.5 stroke-[1.75] group-hover:stroke-[2.25] transition-all"></i>
                @if(request()->routeIs('posts.*'))
                    <div class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary-400"></div>
                @endif
                <span class="absolute bottom-full left-1/2 -translate-x-1/2 translate-y-1 mb-2 px-1.5 py-1 text-xs font-medium text-white whitespace-nowrap bg-[#1c2333] border border-white/7 rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all pointer-events-none">Beranda</span>
            </a>

            <a href="{{ route('connections.index') }}"
               class="relative flex items-center justify-center w-10 h-10 rounded-lg text-white/45 border border-transparent hover:text-primary-400 hover:bg-blue-500/10 hover:border-blue-500/18 hover:-translate-y-0.5 transition-all duration-200 {{ request()->routeIs('connections.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/15' : '' }} group"
               data-tip="Koneksi">
                <i data-lucide="users" class="w-4.5 h-4.5 stroke-[1.75] group-hover:stroke-[2.25] transition-all"></i>
                @if(request()->routeIs('connections.*'))
                    <div class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary-400"></div>
                @endif
                <span class="absolute bottom-full left-1/2 -translate-x-1/2 translate-y-1 mb-2 px-1.5 py-1 text-xs font-medium text-white whitespace-nowrap bg-[#1c2333] border border-white/7 rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all pointer-events-none">Koneksi</span>
            </a>

            <a href="{{ route('events.index') }}"
               class="relative flex items-center justify-center w-10 h-10 rounded-lg text-white/45 border border-transparent hover:text-primary-400 hover:bg-blue-500/10 hover:border-blue-500/18 hover:-translate-y-0.5 transition-all duration-200 {{ request()->routeIs('events.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/15' : '' }} group"
               data-tip="Events">
                <i data-lucide="calendar" class="w-4.5 h-4.5 stroke-[1.75] group-hover:stroke-[2.25] transition-all"></i>
                @if(request()->routeIs('events.*'))
                    <div class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary-400"></div>
                @endif
                <span class="absolute bottom-full left-1/2 -translate-x-1/2 translate-y-1 mb-2 px-1.5 py-1 text-xs font-medium text-white whitespace-nowrap bg-[#1c2333] border border-white/7 rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all pointer-events-none">Events</span>
            </a>

            <a href="{{ route('profile.index') }}"
               class="relative flex items-center justify-center w-10 h-10 rounded-lg text-white/45 border border-transparent hover:text-primary-400 hover:bg-blue-500/10 hover:border-blue-500/18 hover:-translate-y-0.5 transition-all duration-200 {{ request()->routeIs('profile.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/15' : '' }} group"
               data-tip="Profil">
                <i data-lucide="user" class="w-4.5 h-4.5 stroke-[1.75] group-hover:stroke-[2.25] transition-all"></i>
                @if(request()->routeIs('profile.*'))
                    <div class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary-400"></div>
                @endif
                <span class="absolute bottom-full left-1/2 -translate-x-1/2 translate-y-1 mb-2 px-1.5 py-1 text-xs font-medium text-white whitespace-nowrap bg-[#1c2333] border border-white/7 rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all pointer-events-none">Profil</span>
            </a>

            {{-- NOTIFICATION ICON WITH BADGE --}}
            @php
                $unreadCount = App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
            @endphp
            <a href="{{ route('notifications.index') }}"
               class="relative flex items-center justify-center w-10 h-10 rounded-lg text-white/45 border border-transparent hover:text-primary-400 hover:bg-blue-500/10 hover:border-blue-500/18 hover:-translate-y-0.5 transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/15' : '' }} group"
               data-tip="Notifikasi">
                <i data-lucide="bell" class="w-4.5 h-4.5 stroke-[1.75] group-hover:stroke-[2.25] transition-all"></i>
                @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-[#f87171] rounded-full text-[10px] font-bold text-white flex items-center justify-center shadow-lg">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
                @if(request()->routeIs('notifications.*'))
                    <div class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary-400"></div>
                @endif
                <span class="absolute bottom-full left-1/2 -translate-x-1/2 translate-y-1 mb-2 px-1.5 py-1 text-xs font-medium text-white whitespace-nowrap bg-[#1c2333] border border-white/7 rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all pointer-events-none">Notifikasi</span>
            </a>
        </div>

        {{-- Right side --}}
        <div class="flex items-center gap-2">
            {{-- Logout (desktop) --}}
            <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                @csrf
                <button type="submit" 
                        class="flex items-center justify-center w-10 h-10 rounded-lg border border-transparent text-white/45 hover:text-[#f87171] hover:bg-red-500/10 hover:border-red-500/20 hover:-translate-y-0.5 transition-all duration-200"
                        title="Logout">
                    <i data-lucide="log-out" class="w-4.5 h-4.5 stroke-[1.75]"></i>
                </button>
            </form>

            {{-- Hamburger (mobile) --}}
            <button class="md:hidden flex flex-col justify-center items-center w-10 h-10 rounded-lg border border-white/7 bg-none cursor-pointer gap-1.25 hover:bg-white/5 hover:border-white/12 transition-all duration-200 group" 
                    id="hamburger" 
                    onclick="toggleMenu()" 
                    aria-label="Menu">
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full transition-all duration-300 group-[.open]:rotate-45 group-[.open]:translate-x-[4.5px] group-[.open]:translate-y-[4.5px] group-[.open]:bg-white"></span>
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full transition-all duration-300 group-[.open]:opacity-0"></span>
                <span class="block w-4.5 h-[1.5px] bg-white/45 rounded-full transition-all duration-300 group-[.open]:-rotate-45 group-[.open]:translate-x-[4.5px] group-[.open]:-translate-y-[4.5px] group-[.open]:bg-white"></span>
            </button>
        </div>
    </nav>

    {{-- ── Mobile menu ─────────────────────────────────────── --}}
    <div class="hidden flex-col gap-1 bg-[#0d1117]/95 backdrop-blur-xl border-b border-white/7 px-4 py-3 sticky top-14.25 z-40 md:hidden!" id="mobileMenu">
        <a href="{{ route('posts.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/45 text-sm font-medium border border-transparent hover:text-primary-400 hover:bg-blue-500/8 hover:border-blue-500/12 transition-all duration-200 {{ request()->routeIs('posts.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/12' : '' }}">
            <i data-lucide="home" class="w-4 h-4 shrink-0"></i> Beranda
        </a>

        <a href="{{ route('connections.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/45 text-sm font-medium border border-transparent hover:text-primary-400 hover:bg-blue-500/8 hover:border-blue-500/12 transition-all duration-200 {{ request()->routeIs('connections.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/12' : '' }}">
            <i data-lucide="users" class="w-4 h-4 shrink-0"></i> Koneksi
        </a>

        <a href="{{ route('events.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/45 text-sm font-medium border border-transparent hover:text-primary-400 hover:bg-blue-500/8 hover:border-blue-500/12 transition-all duration-200 {{ request()->routeIs('events.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/12' : '' }}">
            <i data-lucide="calendar" class="w-4 h-4 shrink-0"></i> Events
        </a>

        <a href="{{ route('profile.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/45 text-sm font-medium border border-transparent hover:text-primary-400 hover:bg-blue-500/8 hover:border-blue-500/12 transition-all duration-200 {{ request()->routeIs('profile.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/12' : '' }}">
            <i data-lucide="user" class="w-4 h-4 shrink-0"></i> Profil
        </a>

        <a href="{{ route('notifications.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/45 text-sm font-medium border border-transparent hover:text-primary-400 hover:bg-blue-500/8 hover:border-blue-500/12 transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'text-primary-400 bg-blue-500/8 border-blue-500/12' : '' }}">
            <i data-lucide="bell" class="w-4 h-4 shrink-0"></i> Notifikasi
            @php
                $unreadCountMobile = App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
            @endphp
            @if($unreadCountMobile > 0)
                <span class="ml-auto bg-[#f87171] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unreadCountMobile > 9 ? '9+' : $unreadCountMobile }}</span>
            @endif
        </a>

        <div class="h-px bg-white/7 my-1"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/45 text-sm font-medium border border-transparent hover:text-[#f87171] hover:bg-red-500/8 hover:border-red-500/12 transition-all duration-200 w-full text-left">
                <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i> Logout
            </button>
        </form>
    </div>

    {{-- ── Main content ─────────────────────────────────────── --}}
    <main class="relative z-10 max-w-4xl mx-auto px-4 py-6 md:px-6 md:py-8 lg:py-10">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();

        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            const burger = document.getElementById('hamburger');
            menu.classList.toggle('hidden');
            burger.classList.toggle('open');
            
            if (!menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                menu.classList.add('flex');
            } else {
                menu.classList.remove('flex');
                menu.classList.add('hidden');
            }
        }
    </script>

    <style>
        #mobileMenu {
            display: none;
        }
        #mobileMenu.flex {
            display: flex !important;
        }
        #mobileMenu.hidden {
            display: none !important;
        }
        @media (min-width: 768px) {
            #mobileMenu {
                display: none !important;
            }
        }
    </style>
</body>
</html>