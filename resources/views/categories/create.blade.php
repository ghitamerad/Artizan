@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-lg">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Créer une catégorie</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom de la catégorie</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('nom')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="categorie_id" class="block text-gray-700 font-semibold mb-2">Catégorie parente (facultatif)</label>
            <select name="categorie_id" id="categorie_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Aucune</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                @endforeach
            </select>
            @error('categorie_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image" class="block text-gray-700 font-semibold mb-2">Image (facultatif)</label>
            <input type="file" name="image" id="image"
                   class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full
                          file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="fichier_mesure" class="block text-gray-700 font-semibold mb-2">Fichier de mesure (facultatif)</label>
            <input type="file" name="fichier_mesure" id="fichier_mesure"
                   class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full
                          file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" />
            @error('fichier_mesure')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            Créer la catégorie
        </button>
    </form>
</div>
@endsection
