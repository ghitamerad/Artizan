@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center justify-center gap-2">
        <i data-lucide="tag" class="w-8 h-8 text-gray-800"></i>
        Modifier le modèle
    </h2>

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

    <form action="{{ route('modeles.update', $modele) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Valeurs des Attributs -->
        <div
            x-data="{
                selectedAttributs: @js($modele->attributValeurs->pluck('attribut_id')->unique()->values()),
                valeursSelectionnees: @js($modele->attributValeurs->mapWithKeys(fn($v) => [$v->attribut_id => $v->id])),
                attributValeurs: @js($attributs->mapWithKeys(fn($a) => [$a->id => $a->valeurs])->toArray()),
                attributNoms: @js($attributs->pluck('nom', 'id')),
                toggleAttribut(id) {
                    if (this.selectedAttributs.includes(id)) {
                        this.selectedAttributs = this.selectedAttributs.filter(i => i !== id);
                    } else {
                        this.selectedAttributs.push(id);
                    }
                }
            }"
            class="space-y-4"
        >

            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Sélection des Attributs</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($attributs as $attribut)
                        <button
                            type="button"
                            :class="selectedAttributs.includes({{ $attribut->id }})
                                ? 'bg-blue-600 text-white'
                                : 'bg-gray-200 text-gray-800'"
                            class="px-3 py-1 rounded-full text-sm hover:bg-blue-500 hover:text-white transition"
                            @click="toggleAttribut({{ $attribut->id }})"
                        >
                            {{ $attribut->nom }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Sélecteurs de valeur -->
            <template x-for="attributId in selectedAttributs" :key="attributId">
                <div class="mt-4">
                    <label class="block text-gray-700 font-medium mb-2" x-text="attributNoms[attributId]"></label>
                    <div class="flex flex-wrap gap-4">
                        <template x-for="valeur in attributValeurs[attributId]" :key="valeur.id">
                            <label class="flex items-center space-x-2">
                                <input type="radio"
                                       :name="`attribut_valeurs[${attributId}]`"
                                       :value="valeur.id"
                                       class="text-blue-600"
                                       :checked="valeursSelectionnees[attributId] == valeur.id">
                                <span x-text="valeur.nom"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </template>

        </div>

        <hr class="my-8 border-gray-300">

        <!-- Nom -->
        <div>
            <label for="nom" class="block text-gray-700 font-medium">Nom du modèle</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom', $modele->nom) }}" required
                class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-gray-700 font-medium">Description</label>
            <textarea id="description" name="description"
                class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description', $modele->description) }}</textarea>
        </div>

        <!-- Prix -->
        <div>
            <label for="prix" class="block text-gray-700 font-medium">Prix (en DZD)</label>
            <input type="number" id="prix" name="prix" min="0" value="{{ old('prix', $modele->prix) }}" required
                class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Catégorie -->
        <div>
            <label for="categorie_id" class="block text-gray-700 font-medium">Catégorie</label>
            <select id="categorie_id" name="categorie_id" required
                class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Sélectionner une catégorie</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}"
                        {{ old('categorie_id', $modele->categorie_id) == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Image -->
@if ($modele->image)
    <div class="mb-3">
        <label>Image actuelle :</label><br>
        <img src="{{ asset('storage/' . $modele->image) }}" alt="Image actuelle" style="max-width: 200px;">
    </div>
@endif

<div class="mb-3">
    <label for="image">Changer l'image :</label>
    <input type="file" name="image" id="image" class="form-control">
</div>

        <!-- Stock -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="stock" name="stock" value="1"
                {{ old('stock', $modele->stock) ? 'checked' : '' }}
                class="rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
            <label for="stock" class="text-gray-700">Disponible en stock</label>
        </div>

        <!-- Sur commande -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="sur_commande" name="sur_commande" value="1"
                {{ old('sur_commande', $modele->sur_commande) ? 'checked' : '' }}
                class="rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
            <label for="sur_commande" class="text-gray-700">Disponible sur commande</label>
        </div>

        <!-- Fichier Patron -->
        <div>
            <label for="patron" class="block text-gray-700 font-medium">Remplacer le fichier patron (.val)</label>
            <input type="file" id="patron" name="patron" accept=".val"
                class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Fichier XML -->
        <div>
            <label for="xml" class="block text-gray-700 font-medium">Remplacer le fichier de mesures (.xml/.vit)</label>
            <input type="file" id="xml" name="xml" accept=".xml,.vit"
                class="w-full mt-2 p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Boutons -->
        <div class="flex space-x-4">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300">
                Mettre à jour
            </button>
            <a href="{{ route('modeles.index') }}"
                class="bg-gray-400 text-white px-6 py-3 rounded-lg hover:bg-gray-500 transition-all duration-300">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
