<div class="p-4 max-w-7xl mx-auto space-y-6 mt-8">

    {{-- Catégories --}}
    <div class="text-center">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Catégories</h2>
        <div class="flex justify-center">
            <div class="flex overflow-x-auto gap-6 pb-2 px-2 snap-x snap-mandatory">
                @foreach ($categoriesActuelles as $categorie)
                    <div wire:click="selectCategorie({{ $categorie->id }})"
                        class="snap-center shrink-0 w-20 h-20 flex items-center justify-center rounded-full border-2 cursor-pointer transition-all duration-300
                                {{ $categorieSelectionnee === $categorie->id ? 'bg-blue-600 text-white border-blue-600 shadow-lg' : 'bg-white text-gray-700 border-gray-300 hover:border-blue-500' }}"
                        title="{{ $categorie->nom }}">
                        <span class="text-sm text-center px-2">{{ $categorie->nom }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Retour bouton --}}
        @if ($categorieSelectionnee)
            <button wire:click="$set('categorieSelectionnee', null)" class="mt-4 text-sm text-blue-600 hover:underline">
                ← Retour aux catégories
            </button>
        @endif


        {{-- Filtres par attribut --}}
{{-- Bouton pour afficher/masquer les filtres --}}
<div class="text-center mt-6">
    <button wire:click="afficherFormulaireFiltres"
        class="bg-[#05335E] text-white px-4 py-2 rounded-lg hover:bg-[#1A252F] transition-colors duration-300">
        {{ $afficherFiltres ? 'Masquer les filtres' : 'Filtrer les modèles' }}
    </button>
</div>

{{-- Filtres par attribut, affichés seulement si $afficherFiltres est vrai --}}
@if ($afficherFiltres)
    <div class="w-full overflow-x-auto mt-6">
        <div class="flex flex-wrap md:flex-nowrap gap-6">
            @foreach ($attributs as $attribut)
                <div class="min-w-[200px] bg-white border rounded-xl p-4 shadow-md flex-shrink-0">
                    <p class="text-sm font-semibold text-gray-700 mb-3 text-center">{{ $attribut->nom }}</p>
                    <div class="flex flex-col gap-2">
                        @foreach ($attribut->valeurs as $valeur)
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input
                                    type="checkbox"
                                    wire:model.lazy="valeursSelectionnees"
                                    value="{{ $valeur->id }}"
                                    class="rounded"
                                >
                                {{ $valeur->nom }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Boutons appliquer et réinitialiser --}}
        <div class="flex justify-center gap-4 mt-6">
            <button wire:click="appliquerFiltres"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                Appliquer les filtres
            </button>
            <button wire:click="resetFiltres"
                class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors duration-300">
                Réinitialiser
            </button>
        </div>
    </div>
@endif

        <hr class="my-8 border-t border-gray-300">




        <!-- Grille des modèles -->
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
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
                                    Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div>
            {{ $modeles->links() }}
        </div>
    </div>
