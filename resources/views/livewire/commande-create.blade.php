<div class="container mt-5">
    <h2 class="mb-4 text-center text-primary">🛍️ Ajouter une nouvelle commande</h2>

    <div class="card shadow p-4">
        <form wire:submit.prevent="store">
            <div id="modeles-container">
                @foreach ($selectedModeles as $index => $modele)
                    <div class="modele-item d-flex align-items-center mb-3">
                        <div class="w-25 me-2">
                            <label class="form-label fw-bold">Modèle</label>
                            <select class="form-control" wire:model="selectedModeles.{{ $index }}.id" required>
                                <option value="">Sélectionnez un modèle</option>
                                @foreach ($modeles as $m)
                                    <option value="{{ $m->id }}">
                                        {{ $m->nom }} - {{ number_format($m->prix, 2) }} €
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-15 me-2">
                            <label class="form-label fw-bold">Quantité</label>
                            <input type="number" class="form-control" wire:model="selectedModeles.{{ $index }}.quantite" min="1" required>
                        </div>

                        <div class="w-20 me-2">
                            <label class="form-label fw-bold">Custom</label>
                            <select class="form-control" wire:model="selectedModeles.{{ $index }}.custom" required>
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>

                        <div class="w-25 me-2">
                            <label class="form-label fw-bold">Couturière</label>
                            <select class="form-control" wire:model="selectedModeles.{{ $index }}.user_id">
                                <option value="">Sélectionnez une couturière</option>
                                @foreach ($couturieres as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" class="btn btn-danger" wire:click="removeModele({{ $index }})" @if(count($selectedModeles) === 1) disabled @endif>❌</button>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-success mb-3" wire:click="addModele">➕ Ajouter un modèle</button>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">✅ Valider la commande</button>
            </div>
        </form>
    </div>
</div>
