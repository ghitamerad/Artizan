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
        <aside class="w-64 bg-white text-gray-800 border-r border-gray-200 flex flex-col shadow">
            <!-- Logo / Titre -->
            <div class="px-6 py-6 text-center font-bold text-xl text-gray-900 border-b border-gray-200">
                Lebsa Zina
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 text-sm font-medium">
                @if(Auth::check())
                    @php
                        $role = Auth::user()->role;
                    @endphp

                    @if($role === 'admin')
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                            <!-- Icon: Users -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.403 4.209a1 1 0 01-.747.791l-4.65 1.465a1 1 0 01-1.22-.828L12 16m9 0c0 1.105-.895 2-2 2h-3m4-2c0-3.866-3.134-7-7-7H7a7 7 0 00-7 7v5a7 7 0 007 7h10a7 7 0 007-7v-5z"/>
                            </svg>
                            Gestion des utilisateurs
                        </a>
                    @endif

                    @if($role === 'admin' || $role === 'gerante')
                        <a href="{{ route('modeles.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                            <!-- Icon: Dress -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v6h3V3H3zm0 6h3v6H3zm0 6h3v6H3zm18-12h-3v6h3zm0 6h-3v6h3zm0 6h-3v6h3z"/>
                            </svg>
                            Gérer les modèles
                        </a>
                        <a href="{{ route('commandes.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                            <!-- Icon: Clipboard -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 3h-4a2 2 0 00-2 2v14a2 2 0 002 2h4a2 2 0 002-2V5a2 2 0 00-2-2zM5 3a2 2 0 00-2 2v14a2 2 0 002 2h4a2 2 0 002-2V5a2 2 0 00-2-2H5z"/>
                            </svg>
                            Gérer les commandes
                        </a>
                        <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                            <!-- Icon: Clipboard -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 3h-4a2 2 0 00-2 2v14a2 2 0 002 2h4a2 2 0 002-2V5a2 2 0 00-2-2zM5 3a2 2 0 00-2 2v14a2 2 0 002 2h4a2 2 0 002-2V5a2 2 0 00-2-2H5z"/>
                            </svg>
                            gerer categorie
                        </a>
                        <a href="{{ route('attributs.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                            <!-- Icon: Calendar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16m-7 4h7M4 18h16"/>
                            </svg>
                            Gérer les attributs
                        </a>
                        <a href="{{ route('element-patrons.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                            <!-- Icon: Calendar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16m-7 4h7M4 18h16"/>
                            </svg>
                            Gérer les element du patron
                        </a>
                    @endif

                    @if($role === 'couturiere')
                        <a href="{{ route('couturiere.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                            <!-- Icon: Clipboard Check -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-4 4-4-4m0 0V7m4 4H7"/>
                            </svg>
                            Valider les commandes
                        </a>
                        <a href="{{ route('couturiere.commandes') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                            <!-- Icon: Thread -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h6m-6 4h6m-6 4h6"/>
                            </svg>
                            Mes commandes
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                            <!-- Icon: Calendar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16m-7 4h7M4 18h16"/>
                            </svg>
                            Mon planning
                        </a>
                    @endif

                    <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-yellow-100 hover:text-yellow-900 transition">
                        <!-- Icon: Gear -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m9-9H3"/>
                        </svg>
                        Mon profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="pt-4">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                            <!-- Icon: Exit -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9l4-4-4-4m0 0H7m4 4l-4 4"/>
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                @endif
            </nav>
        </aside>

        <!-- Contenu principal -->
        <main class="flex-1 p-10 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>
