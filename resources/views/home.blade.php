<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Notificación -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Navegación -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <!-- Logo -->
                        <a href="#" class="flex items-center py-4 px-2">
                            <span class="font-semibold text-gray-700 text-lg">{{ config('app.name', 'MiApp') }}</span>
                        </a>
                    </div>
                    <!-- Enlaces de navegación principal -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="#" class="py-4 px-2 text-blue-500 border-b-4 border-blue-500 font-semibold">Home</a>
                        <a href="#" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">Servicios</a>
                        <a href="#" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">Sobre Nosotros</a>
                        <a href="#" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">Contacto</a>
                    </div>
                </div>
                <!-- Botones de autenticación -->
                <div class="hidden md:flex items-center space-x-3">
                    @guest
                        <a href="" class="py-2 px-4 font-medium text-gray-500 rounded hover:bg-blue-500 hover:text-white transition duration-300">Log In</a>
                        <a href="" class="py-2 px-4 font-medium text-white bg-blue-500 rounded hover:bg-blue-600 transition duration-300">Registro</a>
                    @else
                        <div class="py-2 px-4 font-medium text-gray-500">
                            Hola, {{ Auth::user()->name }}
                        </div>
                        <a href="" class="py-2 px-4 font-medium text-white bg-red-500 rounded hover:bg-red-600 transition duration-300"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" method="POST" class="hidden">
                            @csrf
                        </form>
                    @endguest
                </div>
                <!-- Botón de menú móvil -->
                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <svg class="w-6 h-6 text-gray-500 hover:text-blue-500" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Menú móvil -->
        <div class="hidden mobile-menu">
            <ul class="px-4 py-3 space-y-2">
                <li><a href="#" class="block text-sm px-2 py-4 text-white bg-blue-500 font-semibold">Home</a></li>
                <li><a href="#" class="block text-sm px-2 py-4 hover:bg-blue-500 hover:text-white transition duration-300">Servicios</a></li>
                <li><a href="#" class="block text-sm px-2 py-4 hover:bg-blue-500 hover:text-white transition duration-300">Sobre Nosotros</a></li>
                <li><a href="#" class="block text-sm px-2 py-4 hover:bg-blue-500 hover:text-white transition duration-300">Contacto</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="py-16 bg-gradient-to-r from-blue-500 to-indigo-600">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center my-12">
            <div class="flex flex-col w-full md:w-2/5 justify-center items-start text-center md:text-left">
                <h1 class="my-4 text-5xl font-bold leading-tight text-white">Bienvenido a {{ config('app.name', 'MiApp') }}</h1>
                <p class="leading-normal text-xl mb-8 text-white">
                    Tu solución completa para la gestión de proyectos y mucho más.
                </p>
                <button class="mx-auto md:mx-0 hover:underline bg-white text-blue-600 font-bold rounded-full my-6 py-4 px-8 shadow-lg hover:shadow-xl transition duration-300">Comenzar</button>
            </div>
            <div class="w-full md:w-3/5 py-6 text-center">
                <img class="w-full md:w-4/5 z-50 mx-auto" src="/api/placeholder/600/400" alt="Hero Image">
            </div>
        </div>
    </div>

    <!-- Características -->
    <section class="bg-white py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Nuestras Características</h2>
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/3 px-4 mb-8">
                    <div class="rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 rounded-full mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Rápido y Eficiente</h3>
                        <p class="text-gray-600">Diseñado para maximizar la productividad y minimizar el tiempo de desarrollo.</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-4 mb-8">
                    <div class="rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 rounded-full mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Seguro y Confiable</h3>
                        <p class="text-gray-600">Implementamos las mejores prácticas de seguridad para proteger tus datos.</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-4 mb-8">
                    <div class="rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 rounded-full mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Soporte Global</h3>
                        <p class="text-gray-600">Disponible en múltiples idiomas y adaptado a diferentes regiones.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-blue-500 py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">¿Listo para comenzar?</h2>
            <p class="text-xl text-white mb-8">Únete a miles de usuarios satisfechos hoy mismo.</p>
            <button class="bg-white text-blue-600 font-bold rounded-full py-4 px-8 shadow-lg hover:shadow-xl transition duration-300">Regístrate Gratis</button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/4 px-4 mb-8">
                    <h2 class="text-xl font-bold mb-4">{{ config('app.name', 'MiApp') }}</h2>
                    <p class="text-gray-400">Transformando la forma en que gestionas tus proyectos desde 2025.</p>
                </div>
                <div class="w-full md:w-1/4 px-4 mb-8">
                    <h2 class="text-xl font-bold mb-4">Enlaces</h2>
                    <ul>
                        <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white transition duration-300">Home</a></li>
                        <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white transition duration-300">Servicios</a></li>
                        <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white transition duration-300">Sobre Nosotros</a></li>
                        <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white transition duration-300">Contacto</a></li>
                    </ul>
                </div>
                <div class="w-full md:w-1/4 px-4 mb-8">
                    <h2 class="text-xl font-bold mb-4">Legal</h2>
                    <ul>
                        <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white transition duration-300">Términos</a></li>
                        <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white transition duration-300">Privacidad</a></li>
                        <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white transition duration-300">Cookies</a></li>
                    </ul>
                </div>
                <div class="w-full md:w-1/4 px-4 mb-8">
                    <h2 class="text-xl font-bold mb-4">Suscríbete</h2>
                    <p class="text-gray-400 mb-4">Mantente actualizado con nuestras últimas novedades.</p>
                    <div class="flex flex-wrap">
                        <input type="email" class="w-full md:w-2/3 p-2 rounded-l" placeholder="Tu email">
                        <button class="w-full md:w-1/3 bg-blue-500 text-white p-2 rounded-r hover:bg-blue-600 transition duration-300">Enviar</button>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'MiApp') }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript para el menú móvil -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector("button.mobile-menu-button");
            const menu = document.querySelector(".mobile-menu");
            
            btn.addEventListener("click", () => {
                menu.classList.toggle("hidden");
            });
        });
    </script>
</body>
</html>
