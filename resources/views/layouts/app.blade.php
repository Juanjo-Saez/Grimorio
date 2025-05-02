<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grimorio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-in-out both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen text-gray-800">

    @if(session('success'))
        <div class="fixed top-5 mb-4 p-4 bg-green-100 text-green-800 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- NAVBAR --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-md px-6 py-4 flex justify-between items-center fixed top-0 inset-x-0 z-50">
        <h1 class="text-xl font-extrabold text-indigo-700">Grimorio</h1>
        <div class="space-x-4 text-sm font-semibold">
            @auth
                <span class="text-gray-600">Saludos, {{ auth()->user()->username }}</span>
                <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200 shadow-md"
                    >
                        Salir del Grimorio
                    </button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Entrar</a>
                <a href="{{ route('signup') }}" class="text-indigo-600 hover:underline">Registro</a>
            @endguest
        </div>
    </nav>

    {{-- CONTENIDO CENTRAL --}}
    <main class="pt-28 flex justify-start px-8 fade-in">
        <div class="bg-white/80 backdrop-blur-xl shadow-2xl rounded-2xl px-10 py-12 w-full ">
            @yield('content')
        </div>
    </main>

</body>
</html>