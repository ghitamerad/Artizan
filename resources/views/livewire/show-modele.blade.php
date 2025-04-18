<div class="min-h-screen bg-[#F5F5DC] p-8">
    <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="grid md:grid-cols-2 gap-8">

            <!-- Détails du modèle -->
            <div class="p-8">
                <div class="mb-6">
                    <span class="bg-[#D4AF37] text-white px-4 py-2 rounded-full text-sm">
                        {{ $modele->categorie->nom }}
                    </span>
                </div>

                <h1 class="text-4xl font-bold text-[#2C3E50] mb-2">{{ $modele->nom }}</h1>

                <!-- Affichage du statut de disponibilité -->
                @if (!$modele->stock && $modele->sur_commande)
                    <p class="text-red-600 font-semibold mb-4">Disponible seulement sur commande</p>
                @elseif (!$modele->stock && !$modele->sur_commande)
                    <p class="text-red-600 font-semibold mb-4">Non disponible</p>
                @elseif ($modele->stock && !$modele->sur_commande)
                    <p class="text-green-600 font-semibold mb-4">Pièce unique</p>
                @endif

                <div class="text-2xl font-bold text-[#2C3E50] mb-6">
                    {{ number_format($modele->prix, 2, ',', ' ') }} €
                </div>

                <div class="prose prose-lg text-gray-600 mb-8">
                    <p>{{ $modele->description }}</p>
                </div>

                <div class="space-y-4">
                    <!-- Bouton Ajouter au panier -->
                    <button wire:click="ajouterAuPanier({{ $modele->id }})"
                        class="w-full bg-[#2C3E50] text-white px-6 py-3 rounded-full hover:bg-[#1a2530] transition-colors duration-300 text-lg">
                        Ajouter au panier
                    </button>
                    @if (session()->has('message'))
    <div class="mt-4 text-center text-green-700 bg-green-100 border border-green-400 px-4 py-2 rounded-lg">
        {{ session('message') }}
    </div>
@endif


                    @if ($modele->sur_commande)
                        <button wire:click="commanderSurMesure({{ $modele->id }})"
                            class="w-full bg-[#D4AF37] text-white px-6 py-3 rounded-full hover:bg-[#b8962e] transition-colors duration-300 text-lg">
                            Commander sur mesure
                        </button>
                        @if (session()->has('message'))
    <div class="mt-4 text-center text-green-700 bg-green-100 border border-green-400 px-4 py-2 rounded-lg">
        {{ session('message') }}
    </div>
@endif

                    @endif

                </div>

                <!-- Informations supplémentaires -->
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <h2 class="text-xl font-semibold text-[#2C3E50] mb-4">Détails du modèle</h2>
                    <ul class="space-y-2 text-gray-600">
                        <li>Référence: #{{ $modele->id }}</li>
                        <li>Catégorie: {{ $modele->categorie->nom }}</li>
                    </ul>
                </div>
            </div>

            <!-- Image du modèle -->
            <div class="p-8">
                @if ($modele->image)
                    <img src="{{ Storage::url($modele->image) }}" alt="{{ $modele->nom }}"
                        class="w-full h-[300px] object-cover rounded-lg shadow-md">
                @else
                    <div class="w-full h-[300px] bg-gray-200 flex items-center justify-center rounded-lg">
                        <svg class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
