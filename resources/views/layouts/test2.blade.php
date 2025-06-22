<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logolebsazinacopy.png') }}" type="image/png">

    <!-- Fonts & Tailwind -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- lucide icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans  text-gray-600 antialiased bg-[#FDFBF1]">

    <div class="min-h-screen">

        <!-- Navbar -->
        <nav class="bg-[#F8F2E1] shadow-md fixed w-full z-10 top-0">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/logoLebsaZinaCopy.png') }}" alt="Logo Lebsa Zina"
                            class="mb-2 h-16 w-auto">
                        <span class="text-2xl font-bold text-[#05335E]">Lebsa Zina</span>
                    </a>
                    <!-- Bouton hamburger -->
                    <button id="menu-toggle" class="md:hidden text-[#05335E] focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>

                    <!-- Liens desktop -->
                    <div class="hidden md:flex space-x-8 items-center">
                        <a href="{{ route('home') }}"
                            class="text-[#05335E] hover:text-[#C19B2C] font-medium">Accueil</a>

                        <a href="{{ route('home') }}"
                            class="text-[#05335E] hover:text-[#C19B2C] font-medium">Catalogue</a>
                        {{-- <a href="{{ route('pret-a-porter') }}"
                            class="text-[#05335E] hover:text-[#C19B2C] font-medium">Pret à Porter</a>
                        <a href="{{ route('sur-mesure') }}" class="text-[#05335E] hover:text-[#C19B2C] font-medium">Sur
                            Mesure</a> --}}
                        <a href="{{ route('devis.demande') }}"
                            class="text-[#05335E] hover:text-[#C19B2C] font-medium">Demander un devis</a>


                    </div>

                    <!-- Champ de recherche Livewire -->
                    <div class="hidden md:block">
                        @livewire('recherche-bar')
                       {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 hover:text-[#2C3E50]"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>--}}
                    </div>


                    <!-- Zone utilisateur -->
                    <div class="hidden md:flex items-center space-x-4">
                        <livewire:panier-bouton />

                        @auth
                            <!-- Menu utilisateur connecté -->
                            <div class="relative group">
                                <button onclick="toggleMenu()"
                                    class="flex items-center text-[#05335E] hover:text-[#C19B2C] focus:outline-none">
                                    <span>{{ auth()->user()->name }}</span>
                                    <svg class="ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
                                    <a href="{{ route('dashboard') }}"
                                        class="block px-4 py-2 text-[#05335E] hover:bg-[#C19B2C] hover:text-white">Tableau
                                        de bord</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-[#05335E] hover:bg-[#C19B2C] hover:text-white">
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 text-[#F7F3E6] bg-[#05335E] rounded-lg hover:bg-[#C19B2C]">Se connecter</a>
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 text-[#05335E] border border-[#05335E] rounded-lg hover:bg-[#C19B2C] hover:text-[#05335E]">S'inscrire</a>
                        @endauth
                    </div>
                </div>

                <!-- Menu mobile (caché par défaut) -->
                <div id="mobile-menu" class="md:hidden hidden flex-col space-y-2 mt-2">
                    <a href="{{ route('home') }}" class="block text-[#05335E] font-medium">Accueil</a>
                    <a href="{{ route('home') }}" class="block text-[#05335E] font-medium">Catalogue</a>
                    {{-- <a href="{{ route('pret-a-porter') }}" class="block text-[#05335E] font-medium">Pret à Porter</a>
                    <a href="{{ route('sur-mesure') }}" class="block text-[#05335E] font-medium">Sur Mesure</a> --}}
                    <a href="{{ route('devis.demande') }}" class="block text-[#05335E] font-medium">Demander un
                        devis</a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-[#05335E] font-medium">Tableau de bord</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left text-[#05335E] font-medium">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block text-[#05335E] font-medium">Se connecter</a>
                        <a href="{{ route('register') }}" class="block text-[#05335E] font-medium">S'inscrire</a>
                    @endauth
                </div>
            </div>
        </nav>


        <!-- Contenu principal -->
<main class="flex-1 p-10">
    @yield('content')
</main>
    <script>
        function toggleMenu() {
            let menu = document.getElementById("user-menu");
            menu.classList.toggle("hidden");
        }

        document.getElementById("menu-toggle").addEventListener("click", function () {
            let mobileMenu = document.getElementById("mobile-menu");
            mobileMenu.classList.toggle("hidden");
        });
    </script>

            <script>
            lucide.createIcons();
        </script>

    </div>
</body>

</html>
