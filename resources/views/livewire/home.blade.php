<div class="min-h-screen bg-[#F7F3E6] p-8">
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


    </div>

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
                        @if ($modele->sur_commande === true)
                            <div class="relative group">
                                <!-- Icône sur commande -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#B87333] cursor-pointer"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
