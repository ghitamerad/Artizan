<div class="min-h-screen bg-[#FDFBF1] p-8">

    <!-- Bouton flottant pour afficher les filtres -->
    <button wire:click="afficherFormulaireFiltres"
        class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 bg-white text-[#05335E] font-semibold px-6 py-3 rounded-full shadow-2xl hover:bg-gray-100 transition duration-300 border border-[#05335E] flex items-center gap-2">

        <i data-lucide="funnel" class="w-5 h-5"></i>
        <span>{{ $afficherFiltres ? 'Masquer' : 'Filtrer' }}</span>
    </button>

    <!-- Overlay flouté -->
    @if ($afficherFiltres)
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-40" wire:click="afficherFormulaireFiltres">
        </div>
    @endif

    <!-- Panneau latéral des filtres -->
    <div class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white shadow-lg z-50 overflow-y-auto transition-transform duration-300 ease-in-out text-black"
        style="{{ $afficherFiltres ? 'transform: translateX(0);' : 'transform: translateX(100%);' }}">
        <!-- En-tête -->
        <div class="p-4 flex justify-between items-center border-b">
            <h2 class="text-lg font-semibold">Filtres</h2>
            <button wire:click="afficherFormulaireFiltres"
                class="text-gray-600 hover:text-black text-2xl">&times;</button>
        </div>

        <!-- Contenu des filtres -->
        <div class="p-4 space-y-4">

            {{-- Attributs dynamiques --}}
            @foreach ($attributs as $attribut)
                <div>
                    <label class="block text-sm font-medium mb-1">{{ $attribut->nom }}</label>
                    @foreach ($attribut->valeurs as $valeur)
                        <label class="inline-flex items-center space-x-2 mr-2 mb-1">
                            <input type="checkbox" wire:model="valeursSelectionnees" value="{{ $valeur->id }}"
                                class="form-checkbox">
                            <span class="text-sm">{{ $valeur->nom }}</span>
                        </label>
                    @endforeach
                </div>
            @endforeach

            {{-- Type de modèle --}}
            <div>
                <label class="block text-sm font-medium mb-1">Type</label>
                <div class="flex gap-3">
                    <button wire:click="filtrerParType('pretaporter')"
                        class="px-3 py-1 border rounded {{ $filtre === 'pretaporter' ? 'bg-black text-white' : '' }}">
                        Prêt-à-porter
                    </button>
                    <button wire:click="filtrerParType('surmesure')"
                        class="px-3 py-1 border rounded {{ $filtre === 'surmesure' ? 'bg-black text-white' : '' }}">
                        Sur-mesure
                    </button>
                    <button wire:click="filtrerParType(null)"
                        class="px-3 py-1 border rounded {{ is_null($filtre) ? 'bg-black text-white' : '' }}">
                        Tous
                    </button>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-4 space-y-2">
                <button wire:click="appliquerFiltres" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800">
                    Appliquer les filtres
                </button>
                <button wire:click="resetFiltres"
                    class="w-full text-black py-2 border border-black rounded hover:bg-gray-100">
                    Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto mb-10">
        <!-- Notification utilisateur avec bouton -->
        <div class="max-w-6xl mx-auto mb-6">
            <div
                class="bg-[#E6F2FF] border-l-4 border-[#1E90FF] text-[#2C3E50] p-5 rounded-xl shadow flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>

                    <div>
                        <p class="font-semibold text-lg">Faites notre <a href="{{ route('questionnaire') }}"
                                class="font-bold underline">
                                questionnaire</a> </p>
                        <p class="text-sm text-gray-700">Trouvez plus rapidement le modèle qui vous correspond.</p>
                    </div>
                </div>
                <a href="{{ route('questionnaire') }}"
                    class="bg-[#05335E] hover:bg-[#1E90FF] text-white font-semibold px-5 py-2 rounded-lg transition-colors duration-300">
                    Commencer
                </a>
            </div>
        </div>

        <!-- Champ de recherche masqué -->
        <div class="w-full lg:flex-1 relative hidden">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un modèle..."
                class="w-full px-5 py-3 pl-12 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-[#2C3E50] focus:border-transparent outline-none text-[#2C3E50]">
            <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
            </svg>
        </div>


        {{-- Carrousel des catégories --}}
        @php
            $categoriesParPage = 5;
            $chunks = $categoriesActuelles ? $categoriesActuelles->chunk($categoriesParPage) : collect();
            $categorieSelectionneeInstance = $categorieSelectionnee
                ? \App\Models\Categorie::with('enfants')->find($categorieSelectionnee)
                : null;
        @endphp

        <div class="text-center mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Catégories</h2>

            <div class="flex items-center justify-between gap-4">
                <!-- Flèche gauche -->
                <button onclick="changeCategoriePage(-1)" id="btn-prev"
                    class="text-gray-500 hover:text-gray-800 disabled:opacity-30 disabled:cursor-not-allowed" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div class="overflow-hidden w-full">
                    <div id="categoriePages" class="flex transition-transform duration-300 ease-in-out"
                        style="transform: translateX(0%);">
                        @if ($chunks->count())
                            @foreach ($chunks as $chunk)
                                <div class="flex gap-6 justify-center w-full shrink-0">
                                    @foreach ($chunk as $categorie)
                                        <div wire:click="selectCategorie({{ $categorie->id }})"
                                            class="flex flex-col items-center w-20 shrink-0 snap-center cursor-pointer"
                                            title="{{ $categorie->nom }}">
                                            <div
                                                class="w-20 h-20 rounded-full border-2 overflow-hidden flex items-center justify-center transition-all duration-300
                                        {{ $categorieSelectionnee === $categorie->id
                                            ? ($categorie->enfants->isEmpty()
                                                ? 'border-green-500 shadow-md'
                                                : 'bg-blue-600 text-white border-blue-600 shadow-lg')
                                            : 'bg-white text-gray-700 border-gray-300 hover:border-blue-500' }}">
                                                @if ($categorie->image)
                                                    <img src="{{ asset('storage/' . $categorie->image) }}"
                                                        alt="{{ $categorie->nom }}"
                                                        class="object-cover w-full h-full" />
                                                @else
                                                    <div
                                                        class="bg-gray-200 w-full h-full flex items-center justify-center text-xs text-gray-500">
                                                        Pas d’image
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="text-sm mt-2 text-center font-semibold text-gray-700 px-1">
                                                {{ $categorie->nom }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @elseif ($categorieSelectionneeInstance)
                            <div class="flex gap-6 justify-center w-full shrink-0">
                                <div wire:click="selectCategorie({{ $categorieSelectionneeInstance->id }})"
                                    class="flex flex-col items-center w-20 shrink-0 snap-center cursor-pointer"
                                    title="{{ $categorieSelectionneeInstance->nom }}">
                                    <div
                                        class="w-20 h-20 rounded-full border-2 overflow-hidden flex items-center justify-center transition-all duration-300
                                {{ $categorieSelectionneeInstance->enfants->isEmpty()
                                    ? 'border-green-500 shadow-md'
                                    : 'bg-blue-600 text-white border-blue-600 shadow-lg' }}">
                                        @if ($categorieSelectionneeInstance->image)
                                            <img src="{{ asset('storage/' . $categorieSelectionneeInstance->image) }}"
                                                alt="{{ $categorieSelectionneeInstance->nom }}"
                                                class="object-cover w-full h-full" />
                                        @else
                                            <div
                                                class="bg-gray-200 w-full h-full flex items-center justify-center text-xs text-gray-500">
                                                Pas d’image
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-sm mt-2 text-center font-semibold text-gray-700 px-1">
                                        {{ $categorieSelectionneeInstance->nom }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Flèche droite -->
                <button onclick="changeCategoriePage(1)" id="btn-next"
                    class="text-gray-500 hover:text-gray-800 disabled:opacity-30 disabled:cursor-not-allowed"
                    @if ($chunks->count() <= 1) disabled @endif>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            @if ($categorieSelectionnee)
                <button wire:click="$set('categorieSelectionnee', null)"
                    class="mt-4 text-sm text-blue-600 hover:underline">
                    ← Retour aux catégories
                </button>
            @endif
        </div>

        <script>
            let currentCategoriePage = 0;
            const totalCategoriePages = {{ $chunks->count() }};
            const container = document.getElementById('categoriePages');

            function changeCategoriePage(direction) {
                currentCategoriePage = Math.max(0, Math.min(currentCategoriePage + direction, totalCategoriePages - 1));
                const translateX = -100 * currentCategoriePage;
                container.style.transform = `translateX(${translateX}%)`;

                document.getElementById('btn-prev').disabled = currentCategoriePage === 0;
                document.getElementById('btn-next').disabled = currentCategoriePage >= totalCategoriePages - 1;
            }
        </script>





        <div class="mb-6 mt-8 border-b border-gray-200">
            <div class="flex space-x-8 text-sm font-medium justify-center">
                <button wire:click="filtrerParType('pretaporter')"
                    class="relative pb-2 transition duration-200 ease-in-out {{ $filtre === 'pretaporter' ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-500' }}">
                    Prêt-à-porter
                    @if ($filtre === 'pretaporter')
                        <span
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 transition-all duration-300"></span>
                    @endif
                </button>

                <button wire:click="filtrerParType('surmesure')"
                    class="relative pb-2 transition duration-200 ease-in-out {{ $filtre === 'surmesure' ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-500' }}">
                    Sur mesure
                    @if ($filtre === 'surmesure')
                        <span
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 transition-all duration-300"></span>
                    @endif
                </button>

                <button wire:click="filtrerParType(null)"
                    class="relative pb-2 transition duration-200 ease-in-out {{ is_null($filtre) ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-500' }}">
                    Tous les modèles
                    @if (is_null($filtre))
                        <span
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 transition-all duration-300"></span>
                    @endif
                </button>
            </div>
        </div>

        <!-- Grille des modèles -->
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 mt-8">
            @foreach ($modeles as $modele)
                <div
                    class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105 overflow-hidden">
                    <a href="{{ route('modele.show', $modele->id) }}" class="block">
                        @if ($modele->image)
                            <img src="{{ asset('storage/' . $modele->image) }}" alt="{{ $modele->nom }}"
                                class="w-full h-80 object-cover">
                        @else
                            <div class="w-full h-80 bg-gray-200 flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-semibold text-[#2C3E50]">{{ $modele->nom }}</h3>

                                <span
                                    class="bg-[#EDEDED] border border-[#05335E] text-[#05335E] px-3 py-1 rounded-full text-sm">
                                    {{ $modele->categorie->nom }}
                                </span>
                            </div>
                            @if ($modele->sur_commande === true)
                                <div class="relative group">
                                    <!-- Icône sur commande -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-[#B87333] cursor-pointer" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-4-8 4-8-9 2L3 3l4 8-4 8 9-2z" />
                                    </svg>

                                    <!-- Tooltip visible au survol -->
                                    <div
                                        class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-xs text-white bg-black rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                        Ce modèle est confectionné sur commande
                                    </div>
                                </div>
                            @endif
                            <div class="flex flex-col gap-3">
                                <span
                                    class="text-2xl font-bold text-[#2C3E50]">{{ number_format($modele->prix, 2, ',', ' ') }}
                                    DZD</span>
                                <button wire:click="ajouterAuPanier({{ $modele->id }})"
                                    class="w-full bg-[#05335E] text-white px-4 py-2 rounded-lg hover:bg-[#1A252F] transition-colors duration-300 flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z" />
                                    </svg>
                                    Voir plus </button>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>



        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $modeles->links() }}
        </div>
    </div>
