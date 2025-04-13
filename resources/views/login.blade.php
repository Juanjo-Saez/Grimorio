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
            <p class="text-sm text-gray-600">Inicia sesión para continuar</p>
        </div>

        <form method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700">Correo electrónico</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700">Contraseña</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200 shadow-md"
            >
                Entrar
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            ¿No tienes cuenta?
            <a href="#" class="text-indigo-600 hover:underline">Unirse al Círculo Arcano</a>
        </p>
    </div>

</body>
</html>

