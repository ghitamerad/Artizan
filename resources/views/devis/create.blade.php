@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-md shadow-md mt-8">
    <h1 class="text-3xl font-semibold mb-6 text-gray-800">Créer un devis</h1>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            {{ implode(' ', $errors->all(':message')) }}
        </div>
    @endif

    <form action="{{ route('devis.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div>
            <label for="categorie_id" class="block mb-2 font-medium text-gray-700">Catégorie <span class="text-red-600">*</span></label>
            <select name="categorie_id" id="categorie_id" required
                class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Choisir une catégorie</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="description" class="block mb-2 font-medium text-gray-700">Description (optionnelle)</label>
            <textarea name="description" id="description" rows="4"
                class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="image" class="block mb-2 font-medium text-gray-700">Image (optionnelle)</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <hr class="my-6 border-gray-300">

        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Choix des options</h2>

        @foreach($attributs as $attribut)
            <div class="mb-6">
                <p class="font-semibold text-gray-900 mb-2">
                    {{ $attribut->nom }}
                    @if($attribut->obligatoire)
                        <span class="text-red-600">*</span>
                    @endif
                </p>

                <div class="flex flex-wrap gap-4">
                    @foreach($attribut->valeurs as $valeur)
                        @php
                            $oldSelected = old("attribut_valeurs.{$attribut->id}");
                            $checked = $oldSelected == $valeur->id
                                || ($attribut->obligatoire && !$oldSelected && $loop->first);
                        @endphp
                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                type="radio"
                                name="attribut_valeurs[{{ $attribut->id }}]"
                                value="{{ $valeur->id }}"
                                class="form-radio text-blue-600 focus:ring-blue-500"
                                {{ $checked ? 'checked' : '' }}
                            >
                            <span class="ml-2 text-gray-700">{{ $valeur->nom }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit"
            class="px-6 py-3 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition duration-300">
            Créer le devis
        </button>
    </form>
</div>
@endsection
