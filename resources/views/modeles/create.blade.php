@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Créer un nouveau modèle</h2>

    @if (session('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('modeles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block text-gray-700 font-medium mb-2">Attributs</label>

            <div class="flex flex-wrap gap-2 mb-3">
                @foreach ($attributs as $attribut)
                    <button type="button"
                            class="px-3 py-1 border border-gray-300 rounded-full text-sm hover:bg-blue-500 hover:text-white transition"
                            onclick="toggleAttribut({{ $attribut->id }}, '{{ $attribut->nom }}')">
                        {{ $attribut->nom }}
                    </button>
                @endforeach
                <a href="{{ route('attributs.index') }}"
                   class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm hover:bg-blue-700 transition-all duration-300">
                    + Ajouter
                </a>
            </div>

            <div id="selected-attributs" class="flex flex-wrap gap-2 mb-2">
                @if(old('attributs'))
                    @foreach(old('attributs') as $id)
                        @php $att = $attributs->firstWhere('id', $id); @endphp
                        @if($att)
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center gap-2">
                                {{ $att->nom }}
                                <button type="button" onclick="removeAttribut({{ $att->id }})" class="text-blue-800 font-bold">×</button>
                                <input type="hidden" name="attributs[]" value="{{ $att->id }}">
                            </span>
                        @endif
                    @endforeach
                @endif
            </div>

            <p class="text-sm text-gray-500 mt-1">Cliquez sur un attribut pour l’ajouter. Cliquez sur la croix pour le retirer.</p>
        </div>



        <!-- Nom du modèle -->
        <div>
            <label for="nom" class="block text-gray-700 font-medium">Nom du modèle</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-gray-700 font-medium">Description</label>
            <textarea id="description" name="description"
                      class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <!-- Prix -->
        <div>
            <label for="prix" class="block text-gray-700 font-medium">Prix (en €)</label>
            <input type="number" id="prix" name="prix" min="0" value="{{ old('prix') }}" required
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Catégorie -->
        <div>
            <label for="categorie_id" class="block text-gray-700 font-medium">Catégorie</label>
            <select id="categorie_id" name="categorie_id" required
                    class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Sélectionner une catégorie</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Stock -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="stock" name="stock" value="1" {{ old('stock', true) ? 'checked' : '' }}
                   class="rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
            <label for="stock" class="text-gray-700">Disponible en stock</label>
        </div>

        <!-- Sur commande -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="sur_commande" name="sur_commande" value="1" {{ old('sur_commande') ? 'checked' : '' }}
                   class="rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
            <label for="sur_commande" class="text-gray-700">Disponible sur commande</label>
        </div>

        <!-- Upload du patron (.val) -->
        <div>
            <label for="patron" class="block text-gray-700 font-medium">Fichier Patron (.val)</label>
            <input type="file" id="patron" name="patron" accept=".val"
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Upload du fichier de mesures (.xml ou .vit) -->
        <div>
            <label for="xml" class="block text-gray-700 font-medium">Fichier de Mesures (.xml ou .vit)</label>
            <input type="file" id="xml" name="xml" accept=".xml,.vit"
                   class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Boutons -->
        <div class="flex space-x-4">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300">
                Créer le modèle
            </button>
            <a href="{{ route('modeles.index') }}"
               class="bg-gray-400 text-white px-6 py-3 rounded-lg hover:bg-gray-500 transition-all duration-300">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
<script>
    function toggleAttribut(id, nom) {
        const existing = document.querySelector(`input[name="attributs[]"][value="${id}"]`);
        if (existing) return;

        const badge = document.createElement('span');
        badge.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center gap-2';
        badge.innerHTML = `
            ${nom}
            <button type="button" onclick="removeAttribut(${id})" class="text-blue-800 font-bold">×</button>
            <input type="hidden" name="attributs[]" value="${id}">
        `;

        document.getElementById('selected-attributs').appendChild(badge);
    }

    function removeAttribut(id) {
        const input = document.querySelector(`input[name="attributs[]"][value="${id}"]`);
        if (input) {
            input.parentElement.remove();
        }
    }
</script>
