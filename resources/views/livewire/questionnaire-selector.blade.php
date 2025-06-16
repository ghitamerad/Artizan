<div class="space-y-8">

    {{-- Fil d’Ariane des catégories --}}
    @if (!empty($categorieSelectionnees))
        <div class="text-sm text-gray-700 bg-gray-100 rounded-lg px-4 py-2 inline-block shadow-sm">
            <strong class="text-blue-600">Parcours :</strong>
            @foreach ($categorieSelectionnees as $cat)
                <span class="mx-1">›</span> <span>{{ $cat->nom }}</span>
            @endforeach
        </div>
    @endif

    {{-- Navigation des catégories --}}
    @if (!$categorieFinale)
        <div>
            <h2 class="text-xl font-semibold text-[#05335E] mb-6">Choisissez une catégorie</h2>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 justify-items-center">
                @foreach ($categoriesActuelles as $categorie)
                    <button wire:click="selectCategorie({{ $categorie->id }})"
                        class="group w-48 flex flex-col items-center justify-center px-4 h-[300px] bg-white border border-gray-200 rounded-full shadow-sm hover:shadow-lg hover:border-blue-400 transition duration-300">

                        @if ($categorie->image)
                            <img loading="lazy" src="{{ asset('storage/' . $categorie->image) }}"
                                alt="{{ $categorie->nom }}"
                                class="w-32 h-48 object-cover rounded-full mb-3 border border-gray-200 shadow" />
                        @else
                            <div
                                class="w-32 h-48 flex items-center justifay-center bg-gray-100 rounded-full mb-3 text-gray-400 text-sm">
                                Aucune image
                            </div>
                        @endif
                        <span
                            class="text-center font-medium text-gray-800 group-hover:text-blue-600 transition">{{ $categorie->nom }}</span>
                    </button>
                @endforeach
            </div>


            @if (!empty($categorieSelectionnees))
                <button wire:click="retour" class="mt-6 text-sm text-blue-600 hover:underline transition">←
                    Revenir</button>
            @endif
        </div>
    @endif

    {{-- Choix des attributs --}}
@if ($categorieFinale)
        <div>
            <h2 class="text-xl font-semibold text-[#05335E] mb-6">Personnalisez votre tenue :
                {{ $categorieFinale->nom }}</h2>
                        @if (count($attributs) === 0)
            <div class="text-gray-600 bg-yellow-100 border border-yellow-300 rounded-lg px-4 py-3 mb-6">
                Aucun attribut n’est lié à cette catégorie pour la personnalisation.
            </div>
        @endif

            @foreach ($attributs as $attributId => $data)
                <div class="mb-8">
                    <label class="block text-lg font-medium text-gray-800 mb-3">{{ $data['nom'] }}</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach ($data['valeurs'] as $valeurId => $valeur)
                            <div wire:click.prevent="$set('selectedValeurs.{{ $attributId }}', '{{ $valeurId }}')"
                                x-data="{ isActive: {{ isset($selectedValeurs[$attributId]) && $selectedValeurs[$attributId] == $valeurId ? 'true' : 'false' }} }" x-on:click="isActive = true"
                                :class="isActive ? 'border-blue-500 ring-2 ring-blue-300 scale-105' : 'border-gray-300'"
                                class="cursor-pointer border rounded-xl p-3 w-32 h-28 flex flex-col items-center justify-center transition-all duration-300 ease-in-out bg-white hover:shadow-lg">
                                @if (!empty($valeur['image']))
                                    <img src="{{ asset('storage/' . $valeur['image']) }}" alt="{{ $valeur['nom'] }}"
                                        class="w-16 h-16 object-cover rounded-md mb-2">
                                @endif

                                <span class="text-sm text-gray-700 text-center">{{ $valeur['nom'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
                            <button wire:click="retour" class="text-sm text-blue-600 hover:underline transition">← Changer de
                    catégorie</button>

            <div class="mt-6 flex gap-4">
                <button wire:click="genererResultats"
                    class="bg-[#05335E] text-white px-6 py-2 rounded-lg hover:bg-[#1A252F] transition duration-300">
                    Voir les modèles correspondants
                </button>

            </div>
        </div>
    @endif

    {{-- Résultats --}}
    @if ($modelesFiltres && count($modelesFiltres))
        <div>
            <!-- Notification - Aucun modèle trouvé -->
            <div class="max-w-6xl mx-auto mb-6 px-4">
                <div
                    class="bg-[#E6F2FF] border-l-4 border-[#1E90FF] text-[#2C3E50] p-5 rounded-xl shadow flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-start md:items-center gap-4">
                        <!-- Icône info -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-[#1E90FF] flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2.25m0 3.75h.008v.008H12V15zm9-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <!-- Texte -->
                        <div>
                            <p class="font-semibold text-lg">
                                Vous n'avez pas trouvé ce que vous cherchez ?
                            </p>
                            <p class="text-sm text-gray-700">
                                Faites une demande de devis personnalisé, et nous vous confectionnerons la tenue
                                parfaite.
                            </p>
                        </div>
                    </div>

                    <!-- Bouton -->
                    <a href="{{ route('devis.demande') }}"
                        class="bg-[#05335E] hover:bg-[#1E90FF] text-white font-semibold px-5 py-2 rounded-lg transition-colors duration-300">
                        Faire une demande de devis
                    </a>
                </div>
            </div>


            <h2 class="text-xl font-semibold text-[#05335E] mb-4">Modèles disponibles :</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($modelesFiltres as $modele)
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
        </div>
    @endif

</div>
