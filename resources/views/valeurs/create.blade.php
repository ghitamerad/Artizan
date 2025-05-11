@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white rounded-xl shadow space-y-6">
    <h2 class="text-xl font-bold">Ajouter une valeur pour : {{ $attribut->nom }}</h2>

    <form action="{{ route('valeurs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- Champ nom --}}
        <div>
            <label for="nom" class="block text-sm font-medium">Nom de la valeur</label>
            <input type="text" name="nom" id="nom" required
                class="w-full mt-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Champ image --}}
        <div>
            <label for="image" class="block text-sm font-medium">Image (optionnel)</label>
            <input type="file" name="image" id="image"
                class="w-full mt-1 border rounded-lg px-4 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        </div>

        {{-- Checkbox custom --}}
        <div>
            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" name="custom" class="rounded border-gray-300 text-blue-600 shadow-sm">
                <span>Valeur personnalisée (custom)</span>
            </label>
        </div>

        {{-- Attribut lié (readonly) --}}
        <div>
            <label for="attribut_id" class="block text-sm font-medium">Attribut lié</label>
            <select name="attribut_id" id="attribut_id" required
                class="w-full mt-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($tousLesAttributs as $a)
                    <option value="{{ $a->id }}" {{ $a->id == $attribut->id ? 'selected' : '' }}>
                        {{ $a->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Ajouter</button>
        </div>
    </form>
</div>
@endsection

