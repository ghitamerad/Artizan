@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-semibold mb-4">Modifier un Attribut</h1>

    <!-- Formulaire de modification d'attribut -->
    <div class="card mb-6 border rounded-lg p-4 shadow-lg">
        <div class="card-header flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold">Modifier l'attribut: {{ $attribut->nom }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('attributs.update', $attribut->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom de l'attribut</label>
                    <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" id="nom" name="nom" value="{{ old('nom', $attribut->nom) }}" required>
                </div>
                <div class="mb-4 flex items-center">
                    <label for="obligatoire" class="mr-2 text-sm font-medium text-gray-700">Obligatoire</label>
                    <input type="checkbox" id="obligatoire" name="obligatoire" value="1" class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $attribut->obligatoire ? 'checked' : '' }}>
                </div>
                <button type="submit" class="w-full py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">Mettre à jour l'attribut</button>
            </form>
        </div>
    </div>
</div>
@endsection
