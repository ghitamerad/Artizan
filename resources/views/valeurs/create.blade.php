@extends('layouts.admin')

@section('content')
    <div class="max-w-lg mx-auto p-6 bg-white rounded-xl shadow space-y-6">
        <h2 class="text-xl font-bold">Ajouter un attribut pour : {{ $attribut->nom }}</h2>

        <form action="{{ route('valeurs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Champ nom --}}
            <div>
                <label for="nom" class="block text-sm font-medium">Nom de l'attribut</label>
                <input type="text" name="nom" id="nom" required
                    class="w-full mt-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Champ image --}}
            <div>
                <label for="image" class="block text-sm font-medium">Image (optionnel)</label>
                <input type="file" name="image" id="image"
                    class="w-full mt-1 border rounded-lg px-4 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            {{-- Checkbox custom
        <div>
            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" name="custom" class="rounded border-gray-300 text-blue-600 shadow-sm">
                <span>Valeur personnalisée (custom)</span>
            </label>
        </div> --}}

            {{-- Attribut lié (readonly) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Attribut lié</label>
                <div class="mt-1 px-4 py-2 border rounded-lg bg-gray-100 text-gray-700">
                    {{ $attribut->nom }}
                </div>
                <input type="hidden" name="attribut_id" value="{{ $attribut->id }}">
            </div>


            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Ajouter</button>
            </div>
        </form>
    </div>
@endsection
