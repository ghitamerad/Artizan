@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier le devis</h2>

    <form action="{{ route('devis.update', $devi) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="description" class="block font-medium text-gray-700">Description</label>
            <input type="text" name="description" id="description"
                   class="mt-1 block w-full border border-gray-300 rounded p-2"
                   value="{{ old('description', $devi->description) }}">
            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="categorie_id" class="block font-medium text-gray-700">Catégorie</label>
            <select name="categorie_id" id="categorie_id"
                    class="mt-1 block w-full border border-gray-300 rounded p-2">
                <option value="">-- Choisir une catégorie --</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}"
                            {{ old('categorie_id', $devi->categorie_id) == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
            @error('categorie_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="image" class="block font-medium text-gray-700">Image</label>
            <input type="file" name="image" id="image"
                   class="mt-1 block w-full border border-gray-300 rounded p-2">
            @if ($devi->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $devi->image) }}" alt="Image actuelle"
                         class="w-24 h-24 object-cover rounded">
                </div>
            @endif
            @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-2">Valeurs d’attribut</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach ($attributs as $attribut)
                    <div class="col-span-2 font-semibold text-gray-700">{{ $attribut->nom }}</div>
                    @foreach ($attribut->valeurs as $valeur)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="attribut_valeurs[]" value="{{ $valeur->id }}"
                                   class="rounded"
                                   {{ in_array($valeur->id, old('attribut_valeurs', $devi->attributValeurs->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <span>{{ $valeur->nom }}</span>
                        </label>
                    @endforeach
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
