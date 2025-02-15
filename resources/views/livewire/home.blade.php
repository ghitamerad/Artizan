<div class="min-h-screen bg-[#F5F5DC] p-8">
    <!-- Barre de recherche et boutons -->
    <div class="max-w-2xl mx-auto mb-12">
        <div class="flex items-center gap-4">
            <div class="flex-1 relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher un modèle..."
                    class="w-full px-4 py-3 pl-12 rounded-full border border-gray-200 shadow-sm focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent outline-none text-[#2C3E50]"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <button 
                class="p-3 rounded-full bg-white shadow-sm hover:shadow-md transition-all duration-300 text-[#2C3E50] hover:bg-[#D4AF37] hover:text-white"
                wire:click="$toggle('filterOpen')"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </button>
            
            <button class="p-3 rounded-full bg-white shadow-sm hover:shadow-md transition-all duration-300 text-[#2C3E50] hover:bg-[#D4AF37] hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Grille des modèles -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 px-8">
        @foreach($modeles as $modele)
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105 overflow-hidden mb-8">
                <a href="{{ route('modele.show', $modele->id) }}" class="block">
                    @if($modele->image)
                        <img src="{{ Storage::url($modele->image) }}" alt="{{ $modele->nom }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
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
                        <div class="flex flex-col gap-4">
                            <span class="text-2xl font-bold text-[#2C3E50]">{{ number_format($modele->prix, 2, ',', ' ') }} €</span>
                            <button 
                                wire:click="ajouterAuPanier({{ $modele->id }})" 
                                class="w-full bg-[#D4AF37] text-black px-4 py-2 rounded-full hover:bg-[#C19B2C] transition-colors duration-300 flex items-center justify-center gap-2"
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
    <div class="mt-8">
        {{ $modeles->links() }}
    </div>
</div> 
