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


    @livewireStyles
</head>

<body class="bg-gray-100">
    @livewireScripts

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#05335E] text-white border-r border-gray-200 flex flex-col">
            <!-- Logo / Titre -->
            <div class="px-6 py-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logoLebsaZinaCopy.png') }}" alt="Logo Lebsa Zina" class="h-12 w-auto">
                    <h1 class="text-xl font-bold">Lebsa Zina</h1>

                </div>
                                    <span class="text-sm ml-14">     {{ ucfirst(Auth::user()->role) }}</span>

            </div>

            <div class="border-b border-gray-200 px-4 py-4 text-sm font-medium">
                        <x-nav-link :href="route('profile')" icon="user" >
                                        {{ Auth::user()->name }}

                        </x-nav-link>
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
                        <x-nav-link :href="route('modeles.index')" icon="scissors" :active="request()->routeIs('modeles.*')">
                            Gérer les modèles
                        </x-nav-link>

                        <x-nav-link :href="route('commandes.index')" icon="clipboard-list" :active="request()->routeIs('commandes.*')">
                            Gérer les commandes
                        </x-nav-link>

                        <x-nav-link :href="route('categories.index')" icon="tag" :active="request()->routeIs('categories.*')">
                            Gérer les catégories
                        </x-nav-link>

                        <x-nav-link :href="route('attributs.index')" icon="adjustments-horizontal" :active="request()->routeIs('attributs.*')">
                            Gérer les attributs
                        </x-nav-link>

                        <x-nav-link :href="route('element-patrons.index')" icon="puzzle" :active="request()->routeIs('element-patrons.*')">
                            Éléments de patron
                        </x-nav-link>
                        <!-- Dropdown Devis -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full px-3 py-2 text-left hover:bg-[#06477D] rounded transition duration-150">
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

                            <div x-show="open" @click.away="open = false" x-transition
                                class="mt-2 ml-6 space-y-1 text-sm">
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
                            Tableau de bord
                        </x-nav-link>

                        <x-nav-link :href="route('couturiere.commandes')" icon="clipboard-list" :active="request()->routeIs('couturiere.commandes')">
                            Mes commandes
                        </x-nav-link>
                    @endif
                @endif
                <br />
                @if (Auth::check())
                    <x-nav-link :href="route('notifications.index')" icon="bell" :active="request()->routeIs('notifications.index')">
                        Notifications
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span
                                class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </x-nav-link>
                @endif

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
