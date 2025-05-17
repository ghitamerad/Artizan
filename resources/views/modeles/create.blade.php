@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Créer un nouveau modèle</h2>

    @if (session('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('modeles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Valeurs des Attributs -->
        <div>
            <label class="block text-gray-700 font-medium mb-2">Choix des options (valeurs d'attributs)</label>
            <div class="space-y-4">
                @foreach($attributs as $attribut)
                <div class="mb-3">
                    <label class="form-label">{{ $attribut->nom }}</label>
                    <select name="attribut_valeurs[{{ $attribut->id }}]" class="form-select">
                        <option value="">-- Aucun(e) --</option>
                        @foreach($attribut->valeurs as $valeur)
                            <option value="{{ $valeur->id }}"
                                {{ old("attribut_valeurs.{$attribut->id}") == $valeur->id ? 'selected' : '' }}>
                                {{ $valeur->valeur }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach
            </div>

            <div class="mt-4">
                <a href="{{ route('attributs.index') }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition duration-300">
                    + Gérer les attributs
                </a>
            </div>
        </div>

        <!-- Nom -->
        <div>
            <label for="nom" class="block text-gray-700 font-medium">Nom du modèle</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-gray-700 font-medium">Description</label>
            <textarea id="description" name="description"
                      class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <!-- Prix -->
        <div>
            <label for="prix" class="block text-gray-700 font-medium">Prix (en €)</label>
            <input type="number" id="prix" name="prix" min="0" value="{{ old('prix') }}" required
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Catégorie -->
        <div>
            <label for="categorie_id" class="block text-gray-700 font-medium">Catégorie</label>
            <select id="categorie_id" name="categorie_id" required
                    class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Sélectionner une catégorie</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Image -->
<div>
    <label for="image" class="block text-gray-700 font-medium">Image du modèle</label>
    <input type="file" id="image" name="image" accept="image/*"
           class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
</div>

        <!-- Stock -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="stock" name="stock" value="1" {{ old('stock', true) ? 'checked' : '' }}
                   class="rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
            <label for="stock" class="text-gray-700">Disponible en stock</label>
        </div>

        <!-- Sur commande -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="sur_commande" name="sur_commande" value="1" {{ old('sur_commande') ? 'checked' : '' }}
                   class="rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
            <label for="sur_commande" class="text-gray-700">Disponible sur commande</label>
        </div>

        <!-- Patron (.val) -->
        <div>
            <label for="patron" class="block text-gray-700 font-medium">Fichier Patron (.val)</label>
            <input type="file" id="patron" name="patron" accept=".val"
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Fichier mesures (.xml/.vit) -->
        <div>
            <label for="xml" class="block text-gray-700 font-medium">Fichier de Mesures (.xml ou .vit)</label>
            <input type="file" id="xml" name="xml" accept=".xml,.vit"
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Boutons -->
        <div class="flex space-x-4">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300">
                Créer le modèle
            </button>
            <a href="{{ route('modeles.index') }}"
               class="bg-gray-400 text-white px-6 py-3 rounded-lg hover:bg-gray-500 transition-all duration-300">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
