<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Komunitas Pelajar</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
      style="background-image: url('{{ asset('assets/unsri_bg.jpg') }}');">

<div class="w-11/12 max-w-6xl bg-white/20 backdrop-blur-lg rounded-3xl shadow-2xl flex overflow-hidden">

    <div class="hidden md:flex w-1/2 p-12 text-white flex-col justify-center bg-black/30">
        <h1 class="text-5xl font-bold mb-6">
            Komunitas <br> Pelajar
        </h1>
        <p class="text-lg mb-4">
            Bangun relasi & berkembang bersama.
        </p>
    </div>

    <div class="w-full md:w-1/2 p-10 bg-white/70 backdrop-blur-xl">

        <h2 class="text-3xl font-bold text-center text-blue-600 mb-8">
            Register
        </h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-2 text-gray-700">Nama</label>
                <input type="text" name="name"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-gray-700">Email</label>
                <input type="email" name="email"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-gray-700">Password</label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition">
                Sign Up
            </button>

            <p class="text-center text-sm mt-6 text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                    Login sekarang
                </a>
            </p>
        </form>

    </div>
</div>
</body>
</html>