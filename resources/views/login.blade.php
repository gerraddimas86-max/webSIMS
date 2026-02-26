<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Komunitas Pelajar</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
      style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e');">

    <div class="w-11/12 max-w-6xl bg-white/20 backdrop-blur-lg rounded-3xl shadow-2xl flex overflow-hidden">

        <!-- LEFT SIDE -->
        <div class="hidden md:flex w-1/2 p-12 text-white flex-col justify-center bg-black/30">
            <h1 class="text-5xl font-bold mb-6">
                Komunitas <br> Pelajar
            </h1>
            <p class="text-lg mb-4">
                Tempat Berkumpulnya Generasi Berprestasi.
            </p>
            <p class="text-sm opacity-80">
                Bangun relasi, berbagi ilmu, dan berkembang bersama dalam satu platform komunitas.
            </p>
        </div>

        <!-- RIGHT SIDE LOGIN -->
        <div class="w-full md:w-1/2 p-10 bg-white/70 backdrop-blur-xl">

            <h2 class="text-3xl font-bold text-center text-blue-600 mb-8">
                Login
            </h2>

            <form method="POST" action="#">
                @csrf

                <div class="mb-5">
                    <label class="block mb-2 text-gray-700">Email</label>
                    <input type="email"
                        class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan email">
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-gray-700">Password</label>
                    <input type="password"
                        class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan password">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition duration-300">
                    Sign In
                </button>

                <p class="text-center text-sm mt-6 text-gray-600">
                    Belum punya akun?
                    <a href="#" class="text-blue-600 hover:underline">Daftar sekarang</a>
                </p>
            </form>

        </div>

    </div>

</body>
</html>