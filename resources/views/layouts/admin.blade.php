<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logolebsazinacopy.png') }}" type="image/png">

    @vite('resources/js/app.js') {{-- pour Vite --}}

    @livewireStyles
</head>

<body class="bg-gray-100">
    @livewireScripts

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#05335E] text-white border-r border-gray-200 flex flex-col h-full">
            <!-- Logo / Titre -->
            <div class="px-6 py-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logoLebsaZinaCopy.png') }}" alt="Logo Lebsa Zina" class="h-12 w-auto">
                    <h1 class="text-xl font-bold">Lebsa Zina</h1>

                </div>

            </div>



            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 text-sm font-medium">


                @if (Auth::check())
                    @php $role = Auth::user()->role; @endphp

                    <!-- Admin uniquement -->
                    @if ($role === 'admin')
                        <x-nav-link :href="route('admin.users.index')" icon="user" :active="request()->routeIs('admin.users.*')">
                            Gestion des utilisateurs
                        </x-nav-link>
                    @endif

                    <!-- Admin & Gérante -->
                    @if (in_array($role, ['admin', 'gerante']))
                        <x-nav-link :href="route('graph')" icon="chart-spline" :active="request()->routeIs('graph')">
                            Statistiques
                        </x-nav-link>
                        <x-nav-link :href="route('categories.index')" icon="tag" :active="request()->routeIs('categories.*')">
                            Gestion des catégories
                        </x-nav-link>

                        <x-nav-link :href="route('attributs.index')" icon="blocks" :active="request()->routeIs('attributs.*')">
                            Gestion des attributs
                        </x-nav-link>


                        <x-nav-link :href="route('element-patrons.index')" icon="puzzle" :active="request()->routeIs('element-patrons.*')">
                            Éléments de patron
                        </x-nav-link>

                        @php
                            $activeModele = in_array(request()->get('filtre'), ['pretaporter', 'surmesure', 'rupture']);
                        @endphp

                        <!-- Dropdown Modèles -->
                        <div x-data="{ open: {{ json_encode($activeModele) }} }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full px-3 py-2 text-left transition-all duration-200 ease-in-out
                                        hover:bg-[#06477D] hover:text-white rounded-full
                                        {{ $activeModele ? 'bg-[#06477D] text-white rounded-full shadow-md' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <i data-lucide="shirt" class="w-5 h-5"></i>
                                    <span>Gestion modèles</span>
                                </div>
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" x-transition class="mt-2 ml-6 space-y-1 text-sm">
                                <x-nav-link :href="route('modeles.index', ['filtre' => 'pretaporter'])" :active="request()->get('filtre') === 'pretaporter'">
                                    Prêt-à-porter
                                </x-nav-link>
                                <x-nav-link :href="route('modeles.index', ['filtre' => 'surmesure'])" :active="request()->get('filtre') === 'surmesure'">
                                    Sur mesure
                                </x-nav-link>
                                <x-nav-link :href="route('modeles.index', ['filtre' => 'rupture'])" :active="request()->get('filtre') === 'rupture'">
                                    En rupture de stock
                                </x-nav-link>
                            </div>
                        </div>



                        @php
                            $activeCommande = in_array(request()->get('filtre'), [
                                'nouvellesCommande',
                                'encours',
                                'terminees',
                                'refusees',
                            ]);
                        @endphp

                        <!-- Dropdown Commandes -->
                        <div x-data="{ open: {{ json_encode($activeCommande) }} }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full px-3 py-2 text-left transition duration-150             hover:bg-[#06477D] hover:text-white rounded-full
                                        {{ $activeCommande ? 'bg-[#06477D] rounded-full text-white shadow-md' : 'hover:bg-[#06477D] hover:text-white rounded' }}">
                                <div class="flex items-center space-x-2">
                                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                    <span>Gestion commandes</span>
                                </div>
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" x-transition class="mt-2 ml-6 space-y-1 text-sm">
                                <x-nav-link :href="route('commandes.index', ['filtre' => 'nouvellesCommande'])" :active="request()->get('filtre') === 'nouvellesCommande'">
                                    Nouvelles commandes
                                </x-nav-link>
                                <x-nav-link :href="route('commandes.index', ['filtre' => 'encours'])" :active="request()->get('filtre') === 'encours'">
                                    Commandes en cours
                                </x-nav-link>
                                <x-nav-link :href="route('commandes.index', ['filtre' => 'terminees'])" :active="request()->get('filtre') === 'terminees'">
                                    Commandes terminées
                                </x-nav-link>
                                <x-nav-link :href="route('commandes.index', ['filtre' => 'refusees'])" :active="request()->get('filtre') === 'refusees'">
                                    Commandes refusées
                                </x-nav-link>
                            </div>
                        </div>



                        @php
                            $activeDevis = in_array(request()->get('filtre'), [
                                'nouvelles',
                                'proposes',
                                'acceptes',
                                'refuses',
                            ]);
                        @endphp

                        <!-- Dropdown Devis -->
                        <div x-data="{ open: {{ json_encode($activeDevis) }} }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full px-3 py-2 text-left transition duration-150 hover:bg-[#06477D] hover:text-white rounded-full
                                        {{ $activeDevis ? 'bg-[#06477D] rounded-full text-white shadow-md' : 'hover:bg-[#06477D] hover:text-white rounded' }}">
                                <div class="flex items-center space-x-2">
                                    <i data-lucide="mail-question" class="w-5 h-5"></i>
                                    <span>Gérer les devis</span>
                                </div>
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" x-transition class="mt-2 ml-6 space-y-1 text-sm">
                                <x-nav-link :href="route('devis.index', ['filtre' => 'nouvelles'])" :active="request()->get('filtre') === 'nouvelles'">
                                    Nouvelles demandes
                                </x-nav-link>
                                <x-nav-link :href="route('devis.index', ['filtre' => 'proposes'])" :active="request()->get('filtre') === 'proposes'">
                                    Devis proposés
                                </x-nav-link>
                                <x-nav-link :href="route('devis.index', ['filtre' => 'acceptes'])" :active="request()->get('filtre') === 'acceptes'">
                                    Devis acceptés
                                </x-nav-link>
                                <x-nav-link :href="route('devis.index', ['filtre' => 'refuses'])" :active="request()->get('filtre') === 'refuses'">
                                    Devis refusés
                                </x-nav-link>
                            </div>
                        </div>
                    @endif
                    <!-- Couturière uniquement -->
                    @if ($role === 'couturiere')
                        <x-nav-link :href="route('couturiere.dashboard')" icon="home" :active="request()->routeIs('couturiere.dashboard')">
                            Nouvelles commandes
                        </x-nav-link>

                        <x-nav-link :href="route('couturiere.commandes')" icon="clipboard-list" :active="request()->routeIs('couturiere.commandes')">
                            Commandes validee
                        </x-nav-link>
                    @endif
                @endif
                <br />
                {{-- @if (Auth::check())
                    <x-nav-link :href="route('notifications.index')" icon="bell" :active="request()->routeIs('notifications.index')">
                        Notifications
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span
                                class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </x-nav-link>
                @endif --}}

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center rounded-full w-full px-3 py-2 text-left text-red-100 hover:text-white hover:bg-red-600 bg-red-500 transition duration-150">
                        <i data-lucide="log-out" class="w-5 h-5 mr-2"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </nav>


        </aside>


        <!-- Contenu principal avec navbar en haut -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">

            <!-- Navbar horizontale -->
            <header class="w-full px-6 py-4 bg-white shadow flex items-center justify-between gap-4">
                <!-- À gauche : Titre Dashboard + Rôle -->
                <div class="text-lg font-semibold text-gray-800">
                    Dashboard
                    @if (Auth::user()->role === 'admin')
                        - Admin
                    @elseif(Auth::user()->role === 'gerante')
                        - Responsable
                    @endif
                </div>
                <div class="flex items-center gap-4">


                    <!-- Notifications -->
                    <a href="{{ route('notifications.index') }}"
                        class="group relative flex items-center px-4 py-2 rounded-full overflow-hidden transition-colors duration-300 bg-gray-100">

                        {{-- Fond bleu animé au hover --}}
                        <span
                            class="absolute top-0 left-0 h-full w-0 group-hover:w-full z-0 transition-all duration-300 ease-in-out bg-[#06477D] rounded-full">
                        </span>

                        {{-- Texte + badge de notifications --}}
                        <span
                            class="relative z-10 flex items-center gap-2 transition-colors duration-300 text-[#C19B2C] group-hover:text-white">
                            <i data-lucide="bell" class="w-5 h-5"></i>


                            Notifications

                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span
                                    class="ml-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold text-white bg-red-600 rounded-full">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </span>
                    </a>


                    <a href="{{ route('profile') }}"
                        class="group relative flex items-center px-4 py-2 rounded-full overflow-hidden transition-colors duration-300 bg-gray-100">

                        {{-- Fond bleu animé qui vient de la gauche --}}
                        <span
                            class="absolute top-0 left-0 h-full w-0 group-hover:w-full z-0 transition-all duration-300 ease-in-out bg-[#06477D] rounded-full">
                        </span>

                        {{-- Texte au-dessus de l'animation --}}
                        <span
                            class="relative z-10 flex items-center gap-2 transition-colors duration-300 text-[#C19B2C] group-hover:text-white">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            {{ Auth::user()->name }}
                        </span>
                    </a>

                </div>

            </header>

            <!-- Contenu principal -->
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>

        <script>
            lucide.createIcons();
        </script>

</body>

</html>
