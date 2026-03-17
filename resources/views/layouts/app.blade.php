<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title>Komunitas Pelajar</title>

@vite('resources/css/app.css')

<script src="https://unpkg.com/lucide@latest"></script>

</head>

<body class="bg-gray-100 min-h-screen">

<!-- NAVBAR -->
<nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">

    <!-- LOGO -->
    <div class="flex items-center gap-2">
        <img src="{{ asset('images/Logo_unsri.jpg') }}" class="w-8 h-8">
        <span class="font-bold text-blue-600 hidden sm:block">
            Komunitas Pelajar
        </span>
    </div>

    <!-- MENU ICON -->
    <div class="flex gap-6 items-center text-gray-600">

        <!-- DASHBOARD -->
        <a href="{{ route('posts.index') }}" class="hover:text-blue-600 cursor-pointer">
            <i data-lucide="home"></i>
        </a>

        <!-- CONNECTION -->
        <a href="{{ route('connections.index') }}" class="hover:text-blue-600 cursor-pointer">
            <i data-lucide="users"></i>
        </a>

        <!-- PROFILE -->
        <a href="{{ route('profile.index') }}" class="hover:text-blue-600 cursor-pointer">
            <i data-lucide="user"></i>
        </a>

        <!-- NOTIFICATION -->
        <a href="#" class="hover:text-blue-600 cursor-pointer">
            <i data-lucide="bell"></i>
        </a>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="hover:text-red-500 cursor-pointer">
                <i data-lucide="log-out"></i>
            </button>
        </form>

    </div>

</nav>

<!-- CONTENT -->
<main class="max-w-4xl mx-auto p-4 sm:p-8">
    @yield('content')
</main>

<script>
    lucide.createIcons();
</script>

</body>
</html>