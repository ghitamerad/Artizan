<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


    @livewireStyles
</head>
<body class="bg-gray-100">
    @livewireScripts

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#05335E] text-white border-r border-gray-200 flex flex-col">
            <!-- Logo / Titre -->
            <div class="px-6 py-6 text-center font-bold text-xl border-b border-gray-200">
                Lebsa Zina
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 text-sm font-medium">
                @if(Auth::check())
                    @php $role = Auth::user()->role; @endphp

                    <!-- Admin uniquement -->
                    @if($role === 'admin')
                        <x-nav-link :href="route('admin.users.index')" icon="user" :active="request()->routeIs('admin.users.*')">
                            Gestion des utilisateurs
                        </x-nav-link>
                    @endif

                    <!-- Admin & Gérante -->
                    @if(in_array($role, ['admin', 'gerante']))
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

                        <x-nav-link :href="route('devis.index')" icon="mail-question" :active="request()->routeIs('devis.*')">
                            Gérer les devis
                        </x-nav-link>
                    @endif
                @endif
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
