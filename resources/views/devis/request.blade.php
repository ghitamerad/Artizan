@extends('layouts.test2')

@section('content')
<div class="min-h-screen bg-[#F7F3E6] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold text-[#05335E] mb-6">Demande de devis personnalisé</h1>

        @if(session('message'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('devis.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="categorie_id" class="block text-sm font-medium text-[#05335E]">Catégorie</label>
                <select name="categorie_id" id="categorie_id" required class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-[#2C3E50] focus:border-[#2C3E50]">
                    <option value="">Choisir une catégorie</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-[#05335E]">Description (optionnelle)</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-[#2C3E50] focus:border-[#2C3E50]">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-[#05335E]">Image d'inspiration (optionnelle)</label>
                <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-4 text-sm focus:ring-[#2C3E50] focus:border-[#2C3E50]">
            </div>

            <div>
                <h2 class="text-lg font-semibold text-[#05335E] mb-2">Personnalisation</h2>
                @foreach($attributs as $attribut)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-[#2C3E50]">{{ $attribut->nom }}
                            @if($attribut->obligatoire)
                                <span class="text-red-500">*</span>
                            @endif
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($attribut->valeurs as $valeur)
                                <label class="inline-flex items-center">
                                    <input type="radio" name="attribut_valeurs[{{ $attribut->id }}]" value="{{ $valeur->id }}" {{ old("attribut_valeurs.{$attribut->id}") == $valeur->id ? 'checked' : '' }} class="text-[#05335E] focus:ring-[#C19B2C]">
                                    <span class="ml-2 text-sm text-[#2C3E50]">{{ $valeur->nom }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#05335E] text-white py-3 px-6 rounded-lg hover:bg-[#C19B2C] transition-colors duration-300 font-semibold">
                    Envoyer la demande
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
