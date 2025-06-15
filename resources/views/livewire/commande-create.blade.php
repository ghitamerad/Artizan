<div class="max-w-6xl mx-auto mt-10 p-8 bg-white shadow-lg rounded-xl">

    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
        Ajouter une nouvelle commande
    </h2>

    @if (session('message'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-gray-100 p-6 rounded-lg shadow-inner">
        <form wire:submit.prevent="store" class="space-y-4">
            <div id="modeles-container" class="space-y-4">
                @foreach ($selectedModeles as $index => $modele)
                    <div class="flex flex-wrap gap-4 items-end bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="w-full sm:w-1/4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Modèle</label>
                            <select wire:model="selectedModeles.{{ $index }}.id"
                                    class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200"
                                    required>
                                <option value="">Sélectionnez un modèle</option>
                                @foreach ($modeles as $m)
                                    <option value="{{ $m->id }}">{{ $m->nom }} - {{ number_format($m->prix, 3) }} DZD</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-1/6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                            <input type="number" min="1"
                                   wire:model="selectedModeles.{{ $index }}.quantite"
                                   class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200"
                                   required>
                        </div>

                        <div class="w-full sm:w-1/6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Custom</label>
                            <select wire:model="selectedModeles.{{ $index }}.custom"
                                    class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200"
                                    required>
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>

                        <div class="w-full sm:w-1/4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Couturière</label>
                            <select wire:model="selectedModeles.{{ $index }}.user_id"
                                    class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200">
                                <option value="">Sélectionnez une couturière</option>
                                @foreach ($couturieres as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-auto">
                            <button type="button"
                                    wire:click="removeModele({{ $index }})"
                                    @if(count($selectedModeles) === 1) disabled @endif
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition disabled:opacity-50">
                                Supprimer
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                <button type="button"
                        wire:click="addModele"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Ajouter un modèle
                </button>
            </div>

            <div class="text-center pt-6">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    Valider la commande
                </button>
            </div>
        </form>
    </div>
</div>
