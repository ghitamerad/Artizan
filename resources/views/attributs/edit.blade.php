@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Modifier un type d’attribut</h1>

    <div class="bg-white rounded-2xl shadow-md p-6 space-y-6 border border-gray-200">
        <div class="border-b pb-4 mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Type d’attribut :
                <span class="text-blue-600">{{ $attribut->nom }}</span>
            </h2>
        </div>

        <form action="{{ route('attributs.update', $attribut->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nom --}}
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom du type d’attribut</label>
                <input type="text" id="nom" name="nom" required
                       value="{{ old('nom', $attribut->nom) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
            </div>

            {{-- Obligatoire --}}
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="obligatoire" name="obligatoire" value="1"
                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring focus:ring-blue-300"
                       {{ $attribut->obligatoire ? 'checked' : '' }}>
                <label for="obligatoire" class="text-sm text-gray-700">Obligatoire</label>
            </div>

            {{-- Bouton de soumission --}}
            <div>
                <button type="submit"
                        class="w-full bg-blue-600 text-white font-semibold py-2 rounded-xl hover:bg-blue-700 transition duration-200">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
