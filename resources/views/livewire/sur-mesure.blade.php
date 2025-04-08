<div class="min-h-screen bg-[#F5F5DC] p-8">
    <!-- Barre de recherche et filtres -->
    <div class="max-w-4xl mx-auto mb-8">
        <div class="flex flex-wrap items-center gap-4 bg-white p-4 rounded-lg shadow-md">
            <div class="flex-1 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un modèle..."
                    class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent outline-none text-[#2C3E50]">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select wire:model="selectedCategorie"
                class="p-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-[#D4AF37] outline-none">
                <option value="">Toutes les catégories</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                @endforeach
            </select>

            <input type="number" wire:model="minPrix" placeholder="Prix min"
                class="p-3 w-24 border rounded-lg shadow-sm focus:ring-2 focus:ring-[#D4AF37] outline-none">
            <input type="number" wire:model="maxPrix" placeholder="Prix max"
                class="p-3 w-24 border rounded-lg shadow-sm focus:ring-2 focus:ring-[#D4AF37] outline-none">
        </div>
    </div>

    <!-- Grille des modèles -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($modeles as $modele)
            <div
                class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <a href="{{ route('modele.show', $modele->id) }}" class="block">
                    @if ($modele->image)
                        <img src="{{ Storage::url($modele->image) }}" alt="{{ $modele->nom }}"
                            class="w-full h-56 object-cover">
                    @else
                        <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z" />
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
                            <span
                                class="text-2xl font-bold text-[#2C3E50]">{{ number_format($modele->prix, 2, ',', ' ') }}
                                €</span>
                            <button
                                class="w-full bg-[#D4AF37] text-black px-4 py-2 rounded-lg hover:bg-[#C19B2C] transition-colors duration-300 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.586-4.586a2 2 0 012.828 0L22 10m-2-2l1.586-1.586a2 2 0 012.828 0L26 8m-2-2h.01M8 22h12a2 2 0 002-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                voir plus
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
