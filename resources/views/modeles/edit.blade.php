@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4 text-gray-700">Modifier le Modèle</h1>

    <form action="{{ route('modeles.update', $modele) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-600">Nom du modèle</label>
            <input type="text" name="nom" value="{{ old('nom', $modele->nom) }}" class="w-full p-2 border border-gray-300 rounded-lg">
            @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-600">Description</label>
            <textarea name="description" class="w-full p-2 border border-gray-300 rounded-lg">{{ old('description', $modele->description) }}</textarea>
        </div>

        <div>
            <label class="block text-gray-600">Prix</label>
            <input type="number" name="prix" value="{{ old('prix', $modele->prix) }}" class="w-full p-2 border border-gray-300 rounded-lg">
            @error('prix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-600">Catégorie</label>
            <select name="categorie_id" class="w-full p-2 border border-gray-300 rounded-lg">
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_id', $modele->categorie_id) == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
            @error('categorie_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-gray-600">En stock</label>
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="stock" value="1" {{ $modele->stock ? 'checked' : '' }} class="mr-2">
                    Oui
                </label>
                <label class="flex items-center">
                    <input type="radio" name="stock" value="0" {{ !$modele->stock ? 'checked' : '' }} class="mr-2">
                    Non
                </label>
            </div>
            @error('en_stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-600">Sur commande</label>
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="sur_commande" value="1" {{ $modele->sur_commande ? 'checked' : '' }} class="mr-2">
                    Oui
                </label>
                <label class="flex items-center">
                    <input type="radio" name="sur_commande" value="0" {{ !$modele->sur_commande ? 'checked' : '' }} class="mr-2">
                    Non
                </label>
            </div>
            @error('sur_commande') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>



        <div>
            <label class="block text-gray-600">Fichier .val (Patron)</label>
            <input type="file" name="patron" class="w-full p-2 border border-gray-300 rounded-lg">
            @error('patron') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @if($modele->patron)
                <p class="mt-2 text-sm text-gray-600">Fichier actuel : <a href="{{ asset('storage/' . $modele->patron) }}" class="text-blue-500 underline" download>Télécharger</a></p>
            @endif
        </div>

        <div>
            <label class="block text-gray-600">Fichier .vit (Mesures XML)</label>
            <input type="file" name="xml" class="w-full p-2 border border-gray-300 rounded-lg">
            @error('xml') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @if($modele->xml)
                <p class="mt-2 text-sm text-gray-600">Fichier actuel : <a href="{{ asset('storage/' . $modele->xml) }}" class="text-blue-500 underline" download>Télécharger</a></p>
            @endif
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Mettre à jour</button>
        </div>
    </form>

    <h2 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Mesures du Modèle</h2>

    <div class="overflow-x-auto">
        <form action="{{ route('mesures.extract', $modele) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">Extraire les mesures</button>
        </form>
        <table class="w-full border-collapse border border-gray-300 rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">Label</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Valeur par défaut</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Variable XML</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modele->mesures as $mesure)
                    <tr class="bg-white hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">{{ $mesure->label }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $mesure->valeur_par_defaut }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $mesure->variable_xml }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <a href="{{ route('mesures.edit', $mesure) }}" class="text-blue-500 underline">Modifier</a>
                            <form action="{{ route('mesures.destroy', $mesure) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Confirmer la suppression ?')" class="text-red-500 underline ml-2">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h3 class="text-lg font-semibold mt-8 text-gray-700">Ajouter une nouvelle mesure</h3>

    <form action="{{ route('mesures.store') }}" method="POST" class="mt-4 space-y-4">
        @csrf
        <input type="hidden" name="modele_id" value="{{ $modele->id }}">

        <div>
            <label class="block text-gray-600">Label</label>
            <input type="text" name="label" value="{{ old('label') }}" required class="w-full p-2 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-gray-600">Valeur par défaut</label>
            <input type="number" name="valeur_par_defaut" value="{{ old('valeur_par_defaut') }}" step="0.01" required class="w-full p-2 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-gray-600">Variable XML</label>
            <input type="text" name="variable_xml" value="{{ old('variable_xml') }}" required class="w-full p-2 border border-gray-300 rounded-lg">
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">Ajouter</button>
    </form>
</div>
@endsection
