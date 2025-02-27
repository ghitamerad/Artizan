@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4 text-gray-700">Modifier la mesure</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('mesures.update', $mesure->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="label" class="block text-gray-600 font-semibold">Nom de la mesure</label>
            <input type="text" name="label" id="label" value="{{ old('label', $mesure->label) }}" required
                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
            @error('label')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="valeur_par_defaut" class="block text-gray-600 font-semibold">Valeur par défaut</label>
            <input type="number" step="0.01" name="valeur_par_defaut" id="valeur_par_defaut"
                   value="{{ old('valeur_par_defaut', $mesure->valeur_par_defaut) }}" required
                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
            @error('valeur_par_defaut')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="variable_xml" class="block text-gray-600 font-semibold">Variable XML</label>
            <input type="text" name="variable_xml" id="variable_xml"
                   value="{{ old('variable_xml', $mesure->variable_xml) }}"
                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
            @error('variable_xml')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Mettre à jour
            </button>
            <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
