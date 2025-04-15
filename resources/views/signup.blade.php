<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center">

    <div class="bg-white/80 backdrop-blur-xl shadow-2xl rounded-2xl px-10 py-12 w-full max-w-md animate-fade-in">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Grimorio</h1>
            <p class="text-sm text-gray-600">Habla, amigo, y entra</p>
        </div>

        <form method="POST" action="{{ route('users.signup') }}" class="space-y-6">
            @csrf

            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700">Nombre de Invocador</label>
                <input
                    id="username"
                    type="username"
                    name="username"
                    placeholder="Kvothe"
                    required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                @error('username')
                    <div class="text-sm px-1 py-1 text-red-500">{{ $message }}</div>
                @enderror

            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700">Instrucciones para el Mensajero</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    placeholder="wizard@tower.jrr"
                    required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                @error('email')
                    <div class="text-sm px-1 py-1 text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700">Palabra de Poder Secreto</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="$uper$ecreto"
                    required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                @error('password')
                    <div class="text-sm px-1 py-1 text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Qué? Repite.</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="$uper$ecreto"
                    required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                @error('password_confirmation')
                    <div class="text-sm px-1 py-1 text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200 shadow-md"
            >
                Ábrete.
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            ¿Ya tienes cuenta?
            <a href="/login" class="text-indigo-600 hover:underline">Habla, amigo, y entra</a>
        </p>

    </div>

</body>
</html>

