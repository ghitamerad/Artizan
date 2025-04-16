<div class="min-h-screen bg-[#F5F5DC] p-8">
    <div class="max-w-6xl mx-auto mb-10">
        <div class="flex flex-col lg:flex-row items-center gap-4 bg-white p-6 rounded-xl shadow-lg">
            <!-- Champ de recherche -->
            <div class="w-full lg:flex-1 relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher un modèle..."
                    class="w-full px-5 py-3 pl-12 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent outline-none text-[#2C3E50]"
                >
                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                </svg>
            </div>

            <!-- Sélection de catégorie -->
            <div class="w-full lg:w-64">
                <select
                    wire:model.live.debounce.300ms="selectedCategorie"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-[#D4AF37] outline-none"
                >
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Prix min -->
            <div class="w-full sm:w-1/2 lg:w-32">
                <input
                    type="number"
                    wire:model.live.debounce.300ms="minPrix"
                    placeholder="Min €"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-[#D4AF37] outline-none"
                >
            </div>

            <!-- Prix max -->
            <div class="w-full sm:w-1/2 lg:w-32">
                <input
                    type="number"
                    wire:model.live.debounce.300ms="maxPrix"
                    placeholder="Max €"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-[#D4AF37] outline-none"
                >
            </div>
                    <!-- Bouton de réinitialisation -->
        <div>
            <button
                wire:click="resetFiltres"
                class="px-5 py-3 bg-[#2C3E50] text-white rounded-lg hover:bg-[#1A252F] transition-colors duration-300"
            >
                Réinitialiser
            </button>
        </div>
        </div>
    </div>


    <!-- Grille des modèles -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($modeles as $modele)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <a href="{{ route('modele.show', $modele->id) }}" class="block">
                    @if($modele->image)
                        <img src="{{ Storage::url($modele->image) }}" alt="{{ $modele->nom }}" class="w-full h-56 object-cover">
                    @else
                        <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-semibold text-[#2C3E50]">{{ $modele->nom }}</h3>
                            <span class="bg-[#D4AF37] text-white px-3 py-1 rounded-full text-sm">
                                {{ $modele->categorie->nom }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-3">
                            <span class="text-2xl font-bold text-[#2C3E50]">{{ number_format($modele->prix, 2, ',', ' ') }} €</span>
                            <button
                                wire:click="ajouterAuPanier({{ $modele->id }})"
                                class="w-full bg-[#D4AF37] text-black px-4 py-2 rounded-lg hover:bg-[#C19B2C] transition-colors duration-300 flex items-center justify-center gap-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z" />
                                </svg>
                                Ajouter au panier
                            </button>
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
