@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier un élément du patron</h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('element-patrons.update', $elementPatron->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Catégorie -->
            <div>
                <label for="categorie_id" class="block text-base font-medium text-gray-700">Catégorie</label>
                <select name="categorie_id" id="categorie_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}"
                            {{ $elementPatron->categorie_id == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
                @error('categorie_id')
                    <p class="text-red-500 text-base mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Valeur d'attribut -->
            <div>
                <label for="attribut_valeur_id" class="block text-base font-medium text-gray-700">Valeur d'attribut</label>
                <select name="attribut_valeur_id" id="attribut_valeur_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">-- Choisir une valeur --</option>
                    @foreach ($valeurs as $valeur)
                        <option value="{{ $valeur->id }}"
                            {{ $elementPatron->attribut_valeur_id == $valeur->id ? 'selected' : '' }}>
                            {{ $valeur->nom }}
                        </option>
                    @endforeach
                </select>
                @error('attribut_valeur_id')
                    <p class="text-red-500 text-base mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fichier actuel -->
            <div>
                <label class="block text-base font-medium text-gray-700">Fichier actuel</label>
                <a href="{{ asset('storage/' . $elementPatron->fichier_patron) }}" target="_blank"
                    class="text-blue-600 underline mt-1 inline-block">
                    Voir le fichier actuel
                </a>
            </div>

            <!-- Nouveau fichier -->
            <div>
                <label for="fichier_patron" class="block text-base font-medium text-gray-700">Nouveau fichier
                    (optionnel)</label>
                <input type="file" name="fichier_patron" id="fichier_patron"
                    class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full
                  file:border-0 file:text-base file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200" />
                @error('fichier_patron')
                    <p class="text-red-500 text-base mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    Enregistrer les modifications
                </button>
                <a href="{{ route('element-patrons.index') }}" class="text-gray-600 hover:underline">Annuler</a>
            </div>
        </form>
    </div>
@endsection
