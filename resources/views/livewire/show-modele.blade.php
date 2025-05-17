<div class="min-h-screen bg-[#F7F3E6] p-8">
    <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Image du modèle -->
            <div class="flex items-center justify-center p-8">
                @if ($modele->image)
                    <img src="{{ asset('storage/' . $modele->image) }}" alt="image du modèle" class="h-[400px] w-[400px] object-cover rounded-lg shadow-md">
                @else
                    <div class="h-[400px] w-[400px] bg-gray-200 flex items-center justify-center rounded-lg">
                        <svg class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Détails du modèle -->
            <div class="p-8 flex flex-col justify-center">
                <!-- Catégorie -->
                <div class="mb-4">
                    <span class="bg-[#C19B2C] text-white px-4 py-2 rounded-full text-sm uppercase tracking-wide shadow-sm">
                        {{ $modele->categorie->nom }}
                    </span>
                </div>

                <!-- Titre -->
                <h1 class="text-4xl font-bold text-[#05335E] mb-4">{{ $modele->nom }}</h1>

                <!-- Attributs en badges (juste après le nom) -->
                @if ($modele->attributValeurs->count())
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach ($modele->attributValeurs->groupBy(fn($valeur) => $valeur->attribut->nom) as $nomAttribut => $valeurs)
                            @foreach ($valeurs as $valeur)
                                <span class="bg-gray-100 text-[#05335E] border border-[#05335E] px-3 py-1 rounded-full text-sm">
                                   <span class="font-bold"> {{ ucfirst($nomAttribut) }} </span> : {{ $valeur->nom }}
                                </span>
                            @endforeach
                        @endforeach
                    </div>
                @endif

                <!-- Statut -->
                @if (!$modele->stock && $modele->sur_commande)
                    <p class="text-red-600 font-semibold mb-4">Disponible seulement sur commande</p>
                @elseif (!$modele->stock && !$modele->sur_commande)
                    <p class="text-red-600 font-semibold mb-4">Non disponible</p>
                @elseif ($modele->stock && !$modele->sur_commande)
                    <p class="text-green-600 font-semibold mb-4">Pièce unique</p>
                @endif

                <!-- Prix -->
                <div class="text-2xl font-bold text-[#05335E] mb-6">
                    {{ number_format($modele->prix, 2, ',', ' ') }} €
                </div>

                <!-- Description -->
                <div class="text-base text-gray-700 leading-relaxed mb-8">
                    <p>{{ $modele->description }}</p>
                </div>

                <!-- Boutons -->
                <div class="space-y-4">
                    <button wire:click="ajouterAuPanier({{ $modele->id }})"
                        class="w-full bg-[#05335E] text-white px-6 py-3 rounded-full hover:bg-[#032846] transition-colors duration-300 text-lg font-semibold">
                        Ajouter au panier
                    </button>

                    @if (session()->has('message'))
                        <div class="text-center text-green-700 bg-green-100 border border-green-400 px-4 py-2 rounded-lg">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($modele->sur_commande)
                        <button wire:click="commanderSurMesure({{ $modele->id }})"
                            class="w-full bg-[#C19B2C] text-white px-6 py-3 rounded-full hover:bg-[#a48823] transition-colors duration-300 text-lg font-semibold">
                            Commander sur mesure
                        </button>
                    @endif
                </div>

                <!-- Détails -->
                <div class="mt-10 border-t border-gray-200 pt-6 space-y-2">
                    <h2 class="text-xl font-semibold text-[#05335E]">Détails du modèle</h2>
                    <ul class="text-gray-700 space-y-1">
                        <li><strong>Référence :</strong> #{{ $modele->id }}</li>
                        <li><strong>Catégorie :</strong> {{ $modele->categorie->nom }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
