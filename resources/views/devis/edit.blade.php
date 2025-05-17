@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-md shadow-md mt-8">
    <h1 class="text-3xl font-semibold mb-6 text-gray-800">Modifier le devis</h1>

    <form action="{{ route('devis.update', $devi) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Catégorie -->
        <div class="mb-4">
            <label for="categorie_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
            <select name="categorie_id" id="categorie_id" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ $categorie->id == $devi->categorie_id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Entrez une description">{{ old('description', $devi->description) }}</textarea>
        </div>

        <!-- Image -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Image actuelle</label>
            @if ($devi->image)
                <img src="{{ asset('storage/' . $devi->image) }}" alt="Image du devis" class="w-48 rounded shadow mt-2 mb-2">
            @else
                <p class="text-gray-500 italic">Aucune image</p>
            @endif

            <label for="image" class="block text-sm font-medium text-gray-700 mt-2">Changer l'image</label>
            <input type="file" name="image" id="image"
                class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer">
        </div>

        <!-- Attributs avec boutons radio -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Attributs</h2>
            @foreach ($attributs as $attribut)
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700">{{ $attribut->nom }}</h3>
                    <div class="flex flex-wrap gap-4 mt-2">
                        @foreach ($attribut->valeurs as $valeur)
                            <label class="inline-flex items-center space-x-2">
                                <input type="radio"
                                    name="attribut_valeurs[{{ $attribut->id }}]"
                                    value="{{ $valeur->id }}"
                                    {{ $devi->attributValeurs->contains($valeur->id) ? 'checked' : '' }}
                                    class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-gray-800">{{ $valeur->valeur ?? $valeur->nom }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Boutons -->
        <div class="flex justify-between items-center">
            <a href="{{ route('devis.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">Annuler</a>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
