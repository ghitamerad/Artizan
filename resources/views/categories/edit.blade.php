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
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
                    required>
            </div>

            <div class="mb-4">
                <label for="categorie_id" class="block text-gray-700 font-medium">Catégorie parente</label>
                <select name="categorie_id" id="categorie_id"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Aucune</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categorie->categorie_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="mb-4">

                <p class="block text-gray-700 font-medium">Image actuelle</p>

                @if ($categorie->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $categorie->image) }}" alt="Image actuelle"
                            class="w-32 h-32 object-cover rounded border">
                    </div>
                @endif

                <label for="image" class="block text-gray-700 font-medium">Image (optionnelle)</label>

                <input type="file" name="image" id="image"
                    class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full
                  file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            </div>

            <div class="mb-6">
                <label for="fichier_mesure" class="block text-gray-700 font-medium">Fichier mesure (optionnel)</label>
                <input type="file" name="fichier_mesure" id="fichier_mesure"
                    class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full
                  file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" />
            </div>


            <div class="flex justify-end space-x-2">
                <a href="{{ route('categories.index') }}"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-gray-700">Annuler</a>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
@endsection
