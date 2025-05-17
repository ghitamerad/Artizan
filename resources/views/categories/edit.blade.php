@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Modifier la catégorie</h1>

    <form action="{{ route('categories.update', $categorie) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nom" class="block text-gray-700 font-medium">Nom</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom', $categorie->nom) }}"
                   class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div class="mb-4">
            <label for="categorie_id" class="block text-gray-700 font-medium">Catégorie parente</label>
            <select name="categorie_id" id="categorie_id"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                <option value="">Aucune</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categorie->categorie_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-gray-700 font-medium">Image (optionnelle)</label>
            <input type="file" name="image" id="image"
                   class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none">
        </div>

        <div class="mb-6">
            <label for="fichier_mesure" class="block text-gray-700 font-medium">Fichier mesure (optionnel)</label>
            <input type="file" name="fichier_mesure" id="fichier_mesure"
                   class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none">
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('categories.index') }}"
               class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-gray-700">Annuler</a>

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
