@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Détail de l'élément du patron</h2>

    <div class="space-y-4">
        <!-- Catégorie -->
        <div>
            <h3 class="text-sm font-semibold text-gray-600">Catégorie :</h3>
            <p class="text-lg text-gray-800">{{ $elementPatron->categorie->nom ?? 'Non défini' }}</p>
        </div>

        <!-- Valeur d'attribut -->
        <div>
            <h3 class="text-sm font-semibold text-gray-600">Valeur d'attribut :</h3>
            <p class="text-lg text-gray-800">{{ $elementPatron->attributValeur->nom ?? 'Non défini' }}</p>
        </div>

        <!-- Fichier du patron -->
        <div>
            <h3 class="text-sm font-semibold text-gray-600">Fichier du patron :</h3>
            @if ($elementPatron->fichier_patron)
                <a href="{{ asset('storage/' . $elementPatron->fichier_patron) }}" target="_blank"
                   class="text-blue-600 underline">Voir ou télécharger le fichier</a>
            @else
                <p class="text-gray-600">Aucun fichier disponible.</p>
            @endif
        </div>
    </div>

    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('element-patrons.index') }}" class="text-sm text-gray-600 hover:underline">← Retour à la liste</a>
        <a href="{{ route('element-patrons.edit', $elementPatron) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
           Modifier
        </a>
    </div>
</div>
@endsection
