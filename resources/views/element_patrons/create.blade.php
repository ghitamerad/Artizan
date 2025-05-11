@extends('layouts.admin')

@section('content')
    <div class="max-w-xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6">Ajouter un Élément de Patron</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('element-patrons.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="categorie_id" class="block mb-1 font-semibold">Catégorie</label>
                <select name="categorie_id" id="categorie_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="attribut_valeur_id" class="block mb-1 font-semibold">Valeur d'attribut</label>
                <select name="attribut_valeur_id" id="attribut_valeur_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Choisir une valeur --</option>
                    @foreach($valeurs as $val)
                        <option value="{{ $val->id }}">{{ $val->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="fichier_patron" class="block mb-1 font-semibold">Fichier Patron</label>
                <input type="file" name="fichier_patron" id="fichier_patron" class="w-full border rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Enregistrer
            </button>
        </form>
    </div>
@endsection