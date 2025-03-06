@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
        Sélectionnez vos mesures pour le modèle : <span class="text-blue-600">{{ $modele->nom }}</span>
    </h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="bg-gray-50 p-4 rounded-lg shadow-md">
            @foreach($mesures as $mesure)
                <div class="mb-4">
                    <label for="mesure_{{ $mesure->id }}" class="block text-gray-700 font-medium">
                        {{ $mesure->label }}
                    </label>
                    <input type="number" step="0.01" name="mesures[{{ $mesure->id }}]" id="mesure_{{ $mesure->id }}"
                        value="{{ $mesure->valeur_par_defaut }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 p-2">
                </div>
            @endforeach
        </div>

        <button type="submit" class="w-full bg-blue-600 text-blue py-2 px-4 rounded-lg hover:bg-blue-700 transition">
            Enregistrer mes mesures
        </button>
    </form>
</div>
@endsection
