@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded-md shadow-md mt-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Générer un patron personnalisé</h1>

    <form action="{{ route('patrons.generer') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Catégorie sélectionnée -->
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Catégorie</label>
            <input type="text" readonly value="{{ $devi->categorie->nom }}"
                   class="w-full px-4 py-2 border rounded-md bg-gray-100 text-gray-800">
            <input type="hidden" name="categorie_id" value="{{ $devi->categorie_id }}">
        </div>

        <!-- Sélection dynamique des attributs -->
        <div
            x-data="{
                selectedAttributs: @js($devi->attributValeurs->pluck('attribut_id')->unique()->values()),
                valeursSelectionnees: @js($devi->attributValeurs->mapWithKeys(fn($v) => [$v->attribut_id => $v->id])),
                attributValeurs: @js($attributs->mapWithKeys(fn($a) => [$a->id => $a->valeurs])->toArray()),
                attributNoms: @js($attributs->pluck('nom', 'id')),
                toggleAttribut(id) {
                    if (this.selectedAttributs.includes(id)) {
                        delete this.valeursSelectionnees[id];
                        this.selectedAttributs = this.selectedAttributs.filter(i => i !== id);
                    } else {
                        this.selectedAttributs.push(id);
                    }
                }
            }"
            class="space-y-6"
        >

            <!-- Liste de tous les attributs en haut -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Ajouter des attributs</h2>
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

            <!-- Affichage des attributs sélectionnés -->
            <template x-for="attributId in selectedAttributs" :key="attributId">
                <div class="bg-gray-50 p-4 rounded-md border relative">
                    <button type="button"
                            @click="toggleAttribut(attributId)"
                            class="absolute top-2 right-2 text-red-600 hover:text-red-800"
                            title="Supprimer cet attribut">
                        &times;
                    </button>

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

        <!-- Bouton final -->
        <div class="text-center mt-6">
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                Générer le patron
            </button>
        </div>

    </form>
</div>
@endsection
