@extends('layouts.admin')

@section('content')

<div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Liste des Éléments de Patron</h1>
            <a href="{{ route('element-patrons.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ajouter</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4">Catégorie</th>
                    <th class="py-2 px-4">Valeur d'attribut</th>
                    <th class="py-2 px-4">Fichier</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($elements as $e)
                <tr class="border-t">
                    <td class="py-2 px-4">{{ $e->categorie->nom }}</td>
                    <td class="py-2 px-4">{{ $e->attributValeur->nom }}</td>
                    <td class="py-2 px-4">
                        <a href="{{ route('element-patrons.show', $e) }}" class="text-blue-600 underline">Voir</a>
                    </td>
                    <td class="py-2 px-4">
                        <a href="{{ route('element-patrons.edit', $e) }}" class="text-yellow-500 mr-2">Modifier</a>
                        <form action="{{ route('element-patrons.destroy', $e) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection