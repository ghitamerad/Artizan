@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $modele->nom }}</h1>

    <div class="mb-6 p-4 bg-gray-100 rounded-lg">
        <p><span class="font-semibold">Description :</span> {{ $modele->description }}</p>
        <p><span class="font-semibold">Prix :</span> {{ $modele->prix }} €</p>
        <p><span class="font-semibold">Catégorie :</span> {{ $modele->categorie->nom }}</p>
    </div>

    @if($modele->patron)
        <a href="{{ asset('storage/' . $modele->patron) }}"
           class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
            📥 Télécharger le patron
        </a>
    @endif

    @if($modele->xml)
        <a href="{{ asset('storage/' . $modele->xml) }}"
           class="inline-block bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
            📥 Télécharger le fichier XML
        </a>
    @endif

    <div class="mt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-3">📏 Mesures associées</h2>

        @if($mesures->isEmpty())
            <p class="text-gray-600">Aucune mesure associée à ce modèle.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse bg-gray-100 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-300 text-gray-800">
                            <th class="px-6 py-3 text-left font-semibold">Nom</th>
                            <th class="px-6 py-3 text-left font-semibold">Valeur par défaut</th>
                            <th class="px-6 py-3 text-left font-semibold">Variable XML</th>
                            <th class="px-6 py-3 text-left font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mesures as $mesure)
                            <tr class="border-b border-gray-200 hover:bg-gray-200 transition">
                                <td class="px-6 py-4">{{ $mesure->label }}</td>
                                <td class="px-6 py-4">{{ $mesure->valeur_par_defaut }}</td>
                                <td class="px-6 py-4">{{ $mesure->variable_xml }}</td>
                                <td class="px-6 py-4 flex space-x-2">
                                    <a href="{{ route('mesures.edit', $mesure) }}"
                                       class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                        ✏️ Modifier
                                    </a>
                                    <form action="{{ route('mesures.destroy', $mesure) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
                                                onclick="return confirm('Confirmer la suppression ?')">
                                            ❌ Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-6 flex space-x-3">
        <a href="{{ route('modeles.index') }}"
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            ◀️ Retour
        </a>

        @can('update', $modele)
            <a href="{{ route('modeles.edit', $modele) }}"
               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                ✏️ Modifier
            </a>
        @endcan

        @can('delete', $modele)
            <form action="{{ route('modeles.destroy', $modele) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
                        onclick="return confirm('Confirmer la suppression ?')">
                    ❌ Supprimer
                </button>
            </form>
        @endcan
    </div>
</div>
@endsection
