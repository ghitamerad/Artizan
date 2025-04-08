<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    @livewireScripts

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-200 text-gray-800 flex flex-col shadow-lg">
            <div class="px-6 py-6 bg-gray-300 text-center font-bold text-lg text-gray-900">
                Lebsa Zina
            </div>

            <nav class="flex-1 px-4 py-4 space-y-3">
                @if(Auth::check())
                    @php
                        $role = Auth::user()->role;
                    @endphp

                    @if($role === 'admin')
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 hover:bg-yellow-400 hover:text-gray-900">
                            👤 Gestion des utilisateurs
                        </a>
                    @endif

                    @if($role === 'admin' || $role === 'gerante')
                        <a href="{{ route('modeles.index') }}"
                           class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 hover:bg-yellow-400 hover:text-gray-900">
                            👗 Gérer les modèles
                        </a>

                        <a href="{{ route('commandes.index') }}"
                           class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 hover:bg-yellow-400 hover:text-gray-900">
                            📋 Gerer les commandes
                        </a>
                    @endif

                    @if($role === 'couturiere')
                    <a href="{{ route('couturiere.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 hover:bg-yellow-400 hover:text-gray-900">
                    📋 Valider les commandes
                 </a>
                        <a href="{{ route('couturiere.commandes') }}"
                           class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 hover:bg-yellow-400 hover:text-gray-900">
                            🧵 Mes commandes
                        </a>

                        <a href=""
                           class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 hover:bg-yellow-400 hover:text-gray-900">
                            📅 Mon planning
                        </a>
                    @endif

                    <a href="{{ route('profile') }}"
                       class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 hover:bg-yellow-400 hover:text-gray-900">
                        ⚙️ Mon profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-6">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-500 text-white font-semibold rounded-lg transition-all duration-300 hover:bg-red-600">
                            🚪 Déconnexion
                        </button>
                    </form>
                @endif
            </nav>
        </aside>

        <!-- Contenu principal -->
        <main class="flex-1 p-10">
            @yield('content')
        </main>
    </div>

</body>
</html>
