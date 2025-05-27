@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Liste des Modèles</h1>

    @if(session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @can('create', App\Models\Modele::class)
        <a href="{{ route('modeles.create') }}"
           class="mb-4 inline-block bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 transition duration-300">
            ➕ Ajouter un Modèle
        </a>
    @endcan

    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-gray-100 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-300 text-gray-800">
                    <th class="px-6 py-3 text-left font-semibold">Nom</th>
                    <th class="px-6 py-3 text-left font-semibold">Prix (DZD)</th>
                    <th class="px-6 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($modeles as $modele)
                    <tr class="border-b border-gray-200 hover:bg-gray-200 transition">
                        <td class="px-6 py-4">{{ $modele->nom }}</td>
                        <td class="px-6 py-4">{{ $modele->prix }} DZD</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ route('modeles.show', $modele) }}"
                               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                👁️ Voir
                            </a>

                            @can('update', $modele)
                                <a href="{{ route('modeles.edit', $modele) }}"
                                   class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                    ✏️ Modifier
                                </a>
                            @endcan

                            @can('delete', $modele)
                                <form action="{{ route('modeles.destroy', $modele) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
                                            onclick="return confirm('Confirmer la suppression ?')">
                                        ❌ Supprimer
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
