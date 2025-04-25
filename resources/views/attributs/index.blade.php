@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Gestion des Attributs</h2>

    @if(session('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Formulaire de création -->
    <form action="{{ route('attributs.store') }}" method="POST" class="mb-6">
        @csrf
        <div class="flex space-x-4">
            <input type="text" name="nom" placeholder="Nom de l'attribut" required
                   class="flex-1 p-2 border border-gray-300 rounded">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Ajouter
            </button>
        </div>
        @error('nom')
            <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
        @enderror
    </form>

    <!-- Liste des attributs -->
    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">Nom</th>
                <th class="p-2 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attributs as $attribut)
                <tr class="border-t">
                    <td class="p-2">{{ $attribut->nom }}</td>
                    <td class="p-2 text-right space-x-2">
                        <!-- Formulaire de modification inline -->
                        <form action="{{ route('attributs.update', $attribut) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <input type="text" name="nom" value="{{ $attribut->nom }}" required
                                   class="p-1 border border-gray-300 rounded w-48">
                            <button class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Modifier</button>
                        </form>

                        <!-- Supprimer -->
                        <form action="{{ route('attributs.destroy', $attribut) }}" method="POST" class="inline-block"
                              onsubmit="return confirm('Supprimer cet attribut ?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Retour -->
    <div class="mt-6">
        <a href="{{ route('modeles.create') }}" class="text-blue-600 hover:underline">
            ← Retour à la création de modèle
        </a>
    </div>
</div>
@endsection
