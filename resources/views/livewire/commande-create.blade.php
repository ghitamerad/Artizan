<div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="mb-6 text-center text-2xl font-semibold text-blue-600">🛍️ Ajouter une nouvelle commande</h2>

    <div class="bg-gray-100 p-6 rounded-lg shadow-md">
        <form wire:submit.prevent="store">
            <div id="modeles-container">
                @foreach ($selectedModeles as $index => $modele)
                    <div class="modele-item flex flex-wrap items-center gap-4 mb-4 bg-white p-4 shadow-sm rounded-lg">
                        <div class="w-full sm:w-1/4">
                            <label class="block text-sm font-medium text-gray-700">Modèle</label>
                            <select class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200" wire:model="selectedModeles.{{ $index }}.id" required>
                                <option value="">Sélectionnez un modèle</option>
                                @foreach ($modeles as $m)
                                    <option value="{{ $m->id }}">{{ $m->nom }} - {{ number_format($m->prix, 2) }} €</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-1/6">
                            <label class="block text-sm font-medium text-gray-700">Quantité</label>
                            <input type="number" class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200" wire:model="selectedModeles.{{ $index }}.quantite" min="1" required>
                        </div>

                        <div class="w-full sm:w-1/6">
                            <label class="block text-sm font-medium text-gray-700">Custom</label>
                            <select class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200" wire:model="selectedModeles.{{ $index }}.custom" required>
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>

                        <div class="w-full sm:w-1/4">
                            <label class="block text-sm font-medium text-gray-700">Couturière</label>
                            <select class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200" wire:model="selectedModeles.{{ $index }}.user_id">
                                <option value="">Sélectionnez une couturière</option>
                                @foreach ($couturieres as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition" wire:click="removeModele({{ $index }})" @if(count($selectedModeles) === 1) disabled @endif>❌</button>
                    </div>
                @endforeach
            </div>

            <button type="button" class="bg-green-500 text-white px-4 py-2 rounded mt-3 hover:bg-green-600 transition" wire:click="addModele">➕ Ajouter un modèle</button>

            <div class="text-center mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">✅ Valider la commande</button>
            </div>
        </form>
    </div>
</div>
