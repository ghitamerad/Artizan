@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-md shadow-md mt-8">
    <h1 class="text-3xl font-semibold mb-6 text-gray-800">Détail du devis</h1>

    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Catégorie :</h2>
        <p class="text-gray-700">{{ $devi->categorie->nom }}</p>
    </div>

    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Description :</h2>
        <p class="text-gray-700 whitespace-pre-line">{{ $devi->description ?? 'Aucune description' }}</p>
    </div>

    @if ($devi->image)
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-2">Image :</h2>
        <img src="{{ asset('storage/' . $devi->image) }}" alt="Image du devis" class="rounded max-w-full h-auto border">
    </div>
    @endif

    <hr class="my-6 border-gray-300">

    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Attributs sélectionnés</h2>

    <div class="space-y-6">
        @foreach ($devi->attributValeurs->groupBy('attribut_id') as $attributId => $valeurs)
            @php
                $attributName = $valeurs->first()->attribut->nom;
            @endphp
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $attributName }}</h3>
                <ul class="space-y-1">
                    @foreach ($valeurs as $valeur)
                        @php
                            // Vérifie si cette valeur a au moins un elementPatron
                            $hasElementPatron = $valeur->elementsPatron->isNotEmpty();
                        @endphp
                        <li class="flex items-center space-x-2">
                            <span class="text-gray-800">{{ $valeur->valeur ?? $valeur->nom ?? 'Valeur inconnue' }}</span>
                            @if($hasElementPatron)
                                <!-- Icône verte -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-label="Présent dans patron">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <!-- Icône rouge -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-label="Absent dans patron">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        <a href="{{ route('devis.index') }}"
           class="inline-block px-6 py-3 bg-gray-600 text-white rounded hover:bg-gray-700 transition duration-300">
            Retour à la liste
        </a>
    </div>
</div>
@endsection
