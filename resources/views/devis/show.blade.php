@extends('layouts.admin')

@section('content')
    @php
        $canGeneratePatron = $devi->attributValeurs
            ->filter(fn($valeur) => $valeur->attribut->obligatoire ?? false) // uniquement les obligatoires
            ->every(
                fn($valeur) => $valeur->elementsPatron->contains(fn($ep) => $ep->categorie_id === $devi->categorie_id),
            );
    @endphp


    <div class="max-w-5xl mx-auto p-6 bg-white rounded-md shadow-md mt-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Détail du devis</h1>

        <!-- Attributs -->
        @if ($devi->attributValeurs->count())
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">Attributs sélectionnés</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach ($devi->attributValeurs as $valeur)
                        @php
                            $attribut = $valeur->attribut;
                            $obligatoire = $attribut->obligatoire ?? false;
                            $hasElementPatron = $valeur->elementsPatron->contains(
                                fn($ep) => $ep->categorie_id === $devi->categorie_id,
                            );
                            $label = $valeur->valeur ?? ($valeur->nom ?? 'Valeur inconnue');
                            $attributNom = $attribut->nom;

                            if ($obligatoire) {
                                $badgeColor = $hasElementPatron
                                    ? 'bg-green-100 text-green-800 border-green-400'
                                    : 'bg-red-100 text-red-800 border-red-400';
                            } else {
                                $badgeColor = 'bg-gray-100 text-gray-700 border-gray-300';
                            }
                        @endphp

                        <span
                            class="inline-flex items-center px-4 py-1.5 border text-sm font-medium rounded-full {{ $badgeColor }}">
                            {{ $attributNom }} : {{ $label }}
                            @if ($obligatoire)
                                @if ($hasElementPatron)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 text-green-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 text-red-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Infos générales -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Catégorie :</h2>
                <p class="text-gray-700">{{ $devi->categorie->nom }}</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-900">Description :</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $devi->description ?? 'Aucune description' }}</p>
            </div>

            @if (!is_null($devi->tarif))
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Tarif proposé :</h2>
                    <p class="text-gray-700">{{ number_format($devi->tarif, 2, ',', ' ') }} DA</p>
                </div>
            @endif

            <div>
                <h2 class="text-xl font-semibold text-gray-900">Statut :</h2>
                @php
                    $statut = $devi->tarif !== null && $devi->statut === 'en_attente' ? 'repondu' : $devi->statut;
                    // Labels et couleurs des statuts
                    $statutLabels = [
                        'en_attente' => [
                            'label' => 'En attente',
                            'color' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        ],
                        'aceptee' => [
                            'label' => 'Acceptée',
                            'color' => 'bg-green-100 text-green-800 border-green-300',
                        ],
                        'refusee' => ['label' => 'Refusée', 'color' => 'bg-red-100 text-red-800 border-red-300'],
                        'repondu' => ['label' => 'Répondu', 'color' => 'bg-blue-100 text-blue-800 border-blue-300'],
                    ];
                @endphp

                <span
                    class="inline-block px-3 py-1 rounded-full text-sm font-medium border {{ $statutLabels[$statut]['color'] }}">
                    {{ $statutLabels[$statut]['label'] }}
                </span>
            </div>
        </div>

        <!-- Image -->
        @if ($devi->image)
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Image :</h2>
                <img src="{{ asset('storage/' . $devi->image) }}" alt="Image du devis"
                    class="rounded-md border shadow w-40">
            </div>
        @endif

        <!-- Formulaire pour proposer un tarif -->
        @if (is_null($devi->tarif))
            <div class="mt-10 bg-gray-100 p-6 rounded-md shadow">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Répondre au devis</h2>
                <form method="POST" action="{{ route('devis.repondre', $devi) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="tarif" class="block text-gray-700 font-semibold">Tarif proposé (DA)</label>
                        <input type="number" step="0.01" name="tarif" id="tarif"
                            class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                        Envoyer la réponse
                    </button>
                </form>
            </div>
        @endif

        <!-- Actions supplémentaires -->
        <div class="mt-8 flex flex-col md:flex-row items-center justify-center gap-4">

            <!-- Bouton retour -->
            <a href="{{ route('devis.index') }}"
                class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition duration-300">
                Retour à la liste des devis
            </a>

            <!-- Si un modèle a déjà été créé -->
            @if ($devi->modele_id)
                <!-- Lien vers le modèle -->
                <a href="{{ route('modeles.show', $devi->modele_id) }}"
                    class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300">
                    Voir le modèle créé
                </a>
            @else
                <!-- Bouton Créer un modèle désactivé si un élément patron est manquant -->
                @if (!$canGeneratePatron)
                    <button disabled class="px-6 py-3 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed">
                        Créer un modèle (éléments de patron manquants)
                    </button>
                @else
                    <!-- Bouton actif -->
                    <a href="{{ route('modeles.create', ['devis_id' => $devi->id]) }}"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                        Créer un modèle
                    </a>
                @endif
            @endif

            @if ($devi->modele_id && $devi->statut === 'aceptee')
                <!-- Bouton Créer une commande -->
                <a href="{{ route('devis.creer_commande', $devi->id) }}"
                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300">
                    Créer une commande
                </a>
            @endif


        </div>

    </div>
@endsection
