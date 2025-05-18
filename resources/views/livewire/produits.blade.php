<div class="p-4 max-w-7xl mx-auto space-y-6">

    {{-- Catégories --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Catégories</h2>
        <div class="flex overflow-x-auto gap-3 pb-2">
            @foreach ($categoriesActuelles as $categorie)
                <div
                    wire:click="selectCategorie({{ $categorie->id }})"
                    class="flex items-center justify-center px-4 py-2 rounded-full text-sm cursor-pointer
                           whitespace-nowrap border transition
                           {{ $categorieSelectionnee === $categorie->id ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">
                    {{ $categorie->nom }}
                </div>
            @endforeach
        </div>

        {{-- Retour bouton --}}
        @if ($categorieSelectionnee)
            <button wire:click="$set('categorieSelectionnee', null)" class="mt-3 text-sm text-blue-600 hover:underline">
                ← Retour aux catégories
            </button>
        @endif
    </div>

{{-- Filtres par attribut --}}
<div class="w-full overflow-x-auto">
    <div class="flex flex-wrap md:flex-nowrap gap-6">
        @foreach ($attributs as $attribut)
            <div class="min-w-[200px] bg-white border rounded-lg p-3 shadow-sm flex-shrink-0">
                <p class="text-sm font-semibold text-gray-700 mb-2 text-center">{{ $attribut->nom }}</p>
                <div class="flex flex-col gap-2">
                    @foreach ($attribut->valeurs as $valeur)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                wire:model.defer="valeursSelectionnees"
                                wire:change="$refresh"
                                value="{{ $valeur->id }}"
                                class="rounded"
                            >
                            {{ $valeur->nom }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>


    {{-- Liste des modèles --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">
        @forelse ($modeles as $modele)
            <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition border">
                <h3 class="text-lg font-bold text-gray-800">{{ $modele->nom }}</h3>
                <p class="text-sm text-gray-500 mt-1">Catégorie : {{ $modele->categorie->nom ?? '-' }}</p>
                <div class="mt-4 text-right">
                    <span class="text-blue-600 font-semibold text-lg">{{ $modele->prix }} €</span>
                </div>
            </div>
        @empty
            <p class="col-span-3 text-center text-gray-500">Aucun modèle trouvé.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div>
        {{ $modeles->links() }}
    </div>
</div>
