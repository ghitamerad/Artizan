@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Détails du devis</h2>

    <div class="space-y-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Description</h3>
            <p class="text-gray-600">{{ $devi->description ?? '—' }}</p>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-700">Catégorie</h3>
            <p class="text-gray-600">{{ $devi->categorie->nom ?? 'Non spécifiée' }}</p>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-700">Image</h3>
            @if ($devi->image)
                <img src="{{ asset('storage/' . $devi->image) }}" alt="Image du devis"
                     class="w-40 h-40 object-cover rounded border mt-2">
            @else
                <p class="text-gray-600">Aucune image disponible.</p>
            @endif
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-700">Utilisateur</h3>
            <p class="text-gray-600">{{ $devi->utilisateur->name ?? '—' }}</p>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-700">Valeurs d’attribut</h3>
            @if ($devi->attributValeurs->count())
                <ul class="list-disc list-inside text-gray-600">
                    @foreach ($devi->attributValeurs as $valeur)
                        <li>{{ $valeur->nom }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">Aucune valeur sélectionnée.</p>
            @endif
        </div>
    </div>

    <div class="mt-6 flex justify-between">
        <a href="{{ route('devis.edit', $devi) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
            Modifier
        </a>
        <form action="{{ route('devis.destroy', $devi) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Supprimer
            </button>
        </form>
    </div>
</div>
@endsection
