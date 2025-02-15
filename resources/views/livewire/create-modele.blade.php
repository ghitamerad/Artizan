<div>
    <form wire:submit.prevent="createModele">
        <div>
            <label>Nom du modèle</label>
            <input type="text" wire:model="nom">
            @error('nom') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Description</label>
            <textarea wire:model="description"></textarea>
        </div>

        <div>
            <label>Prix</label>
            <input type="number" wire:model="prix">
            @error('prix') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Catégorie</label>
            <select wire:model="categorie_id">
                <option value="">Sélectionner une catégorie</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                @endforeach
            </select>
            @error('categorie_id') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Fichier .val (Patron)</label>
            <input type="file" wire:model="patron">
            @error('patron') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Fichier .vit (Mesures XML)</label>
            <input type="file" wire:model="xml">
            @error('xml') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Ajouter le modèle</button>
        <a href="{{ route('generate.download.patron', $modele->id) }}" class="btn btn-primary">
    Télécharger le Patron en PDF
</a>

        @if (session()->has('message'))
            <p>{{ session('message') }}</p>
        @endif
    </form>
</div>
