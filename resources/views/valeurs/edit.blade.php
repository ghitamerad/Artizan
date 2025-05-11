@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto p-6 space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Modifier la valeur : {{ $valeur->nom }}</h2>

    <form action="{{ route('valeurs.update', $valeur) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-2xl shadow">
        @csrf
        @method('PUT')

        {{-- Champ Nom élargi --}}
        <div>
            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom', $valeur->nom) }}"
                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base py-2 px-4" required>
            @error('nom')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image actuelle --}}
        @if($valeur->image)
            <div>
                <label class="block text-sm font-medium text-gray-700">Image actuelle :</label>
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $valeur->image) }}" alt="Image actuelle" class="w-32 rounded shadow border border-gray-200">
                </div>
            </div>
        @endif

        {{-- Champ Image avec style personnalisé --}}
        <div>
            <label for="image" class="block text-sm font-medium">Image (optionnel)</label>
            <input type="file" name="image" id="image"
                class="w-full mt-1 border rounded-lg px-4 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Personnalisable --}}
        <div class="flex items-center space-x-2">
            <input type="checkbox" name="custom" id="custom"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring focus:ring-blue-200"
                {{ old('custom', $valeur->custom) ? 'checked' : '' }}>
            <label for="custom" class="text-sm text-gray-700">Personnalisable ?</label>
        </div>

        {{-- Boutons --}}
        <div class="flex space-x-4">
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">Mettre à jour</button>
            <a href="{{ route('attributs.index') }}"
                class="bg-gray-300 text-gray-800 px-4 py-2 rounded-xl hover:bg-gray-400 transition">Annuler</a>
        </div>
    </form>
</div>
@endsection
