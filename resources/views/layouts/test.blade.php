<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Tailwind -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen bg-gray-100">

        <!-- Navbar -->
        <nav class="bg-[#F5F5DC] shadow-md fixed w-full z-10 top-0">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="/" class="text-2xl font-bold text-[#2C3E50]">Lebsa Zina</a>

                    <!-- Liens de navigation -->
                    <div class="hidden md:flex space-x-8">
                        <a href="{{ route('dashboard') }}" class="text-[#2C3E50] hover:text-[#D4AF37] font-medium">Accueil</a>
                        <a href="#" class="text-[#2C3E50] hover:text-[#D4AF37] font-medium">Produits</a>
                        <a href="#" class="text-[#2C3E50] hover:text-[#D4AF37] font-medium">Contact</a>
                    </div>

                    <!-- Zone utilisateur -->
                    <div class="flex items-center space-x-4">
                        <!-- Bouton Panier -->
                        <div class="relative group">
                            <button
                                class="p-2 bg-[#D4AF37] text-white rounded-full shadow-lg hover:bg-[#C19B2C] transition duration-300 ease-in-out">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l1 5m0 0h13l1-5h2m-2 5l-1 9H6L5 8m0 0H3m7 13a2 2 0 100-4 2 2 0 000 4zm7 0a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                                <!-- Badge du nombre d'articles -->
                                <span id="cart-count"
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full hidden">
                                    0
                                </span>
                            </button>

                            <!-- Dropdown Panier -->
                            <div id="cart-dropdown"
                                class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg hidden group-hover:block">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-[#2C3E50]">Panier</h3>
                                    <ul id="cart-items" class="mt-2 space-y-2">
                                        <li class="text-sm text-gray-700">Modèle 1</li>
                                        <li class="text-sm text-gray-700">Modèle 2</li>
                                        <li class="text-sm text-gray-700">Modèle 3</li>
                                    </ul>
                                    <a href="{{ route('panier') }}" class="mt-4 w-full bg-[#D4AF37] text-white py-1 px-2 rounded-lg hover:bg-[#C19B2C]">
                                        Afficher
                                    </a>
                                </div>
                            </div>
                        </div>

                        <script>
                            // Simule le nombre d'articles dans le panier
                            document.addEventListener("DOMContentLoaded", function() {
                                let cartCount = 3; // Change cette valeur dynamiquement
                                let badge = document.getElementById("cart-count");

                                if (cartCount > 0) {
                                    badge.innerText = cartCount;
                                    badge.classList.remove("hidden");
                                }
                            });
                        </script>

                        @auth
                            <!-- Menu utilisateur connecté -->
                            <div class="relative group">
                                <button class="flex items-center text-[#2C3E50] hover:text-[#D4AF37] focus:outline-none"
                                    onclick="toggleMenu()">
                                    <span>{{ auth()->user()->name }}</span>
                                    <svg class="ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
                                    <a href="{{ route('dashboard') }}"
                                        class="block px-4 py-2 text-[#2C3E50] hover:bg-[#D4AF37] hover:text-white">Tableau de bord</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-[#2C3E50] hover:bg-[#D4AF37] hover:text-white">
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <script>
                                function toggleMenu() {
                                    let menu = document.getElementById("user-menu");
                                    menu.classList.toggle("hidden");
                                }
                            </script>
                        @else
                            <!-- Liens Login / Register -->
                            <a href="{{ route('login') }}" class="px-4 py-2 text-white bg-[#D4AF37] rounded-lg hover:bg-[#C19B2C]">Se connecter</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 text-[#2C3E50] border border-[#D4AF37] rounded-lg hover:bg-[#D4AF37] hover:text-white">S'inscrire</a>
                        @endauth
                    </div>

                    <!-- Menu mobile -->
                    <button class="md:hidden text-[#2C3E50] focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <div class="pt-20">
            {{ $slot }}
        </div>

    </div>
</body>

</html>
