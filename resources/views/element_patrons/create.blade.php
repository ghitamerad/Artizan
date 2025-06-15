@extends('layouts.admin')

@section('content')
    <div class="max-w-xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Ajouter un Élément de Patron</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-base">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('element-patrons.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-2xl shadow">
            @csrf

            {{-- Catégorie --}}
            <div>
                <label for="categorie_id" class="block text-base font-medium text-gray-700 mb-1">Catégorie</label>
                <select name="categorie_id" id="categorie_id"
                    class="w-full border-gray-300 rounded-xl shadow-base px-4 py-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Valeur d'attribut --}}
            <div>
                <label for="attribut_valeur_id" class="block text-base font-medium text-gray-700 mb-1">Valeur d'attribut</label>
                <select name="attribut_valeur_id" id="attribut_valeur_id"
                    class="w-full border-gray-300 rounded-xl shadow-base px-4 py-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                    <option value="">-- Choisir une valeur --</option>
                    @foreach($valeurs as $val)
                        <option value="{{ $val->id }}">{{ $val->nom }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Fichier patron --}}
            <div>
                <label for="fichier_patron" class="block text-base font-medium text-gray-700 mb-1">Fichier Patron</label>
                <input type="file" name="fichier_patron" id="fichier_patron"
                    class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full
                           file:border-0 file:text-base file:font-semibold file:bg-purple-50
                           file:text-purple-700 hover:file:bg-purple-100">
            </div>

            {{-- Bouton --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 transition">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
@endsection
