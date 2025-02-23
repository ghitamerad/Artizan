<!-- resources/views/livewire/home.blade.php -->
<script>
    Livewire.on('panierMisAJour', () => {
        location.reload();
    });
</script>

<div class="container mx-auto p-6">
    <!-- Header avec Recherche et Panier -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Boutique Traditionnelle</h1>
        <div class="flex gap-4">
            <input type="text" wire:model="search" placeholder="Rechercher..."
                class="border rounded-lg px-4 py-2 w-64">
                <a href="{{ route('panier') }}" class="btn btn-primary">

                🛒 <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-2">{{ $panierCount }}</span>
            </a>
            <a href="{{ route('panier') }}">
    Panier (<span id="panier-count">{{ $panierCount }}</span>)
</a>

        </div>
    </div>

    <!-- Filtres par Catégorie et Prix -->
    <div class="flex gap-4 mb-6">
        <select wire:model="selectedCategorie" class="border rounded-lg px-4 py-2">
            <option value="">Toutes les catégories</option>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
            @endforeach
        </select>

        <input type="number" wire:model="minPrix" placeholder="Prix min" class="border rounded-lg px-4 py-2 w-24">
        <input type="number" wire:model="maxPrix" placeholder="Prix max" class="border rounded-lg px-4 py-2 w-24">
    </div>

    <!-- Grid des Modèles -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($modeles as $modele)
            <div class="border rounded-lg overflow-hidden shadow-lg">
                <img src="{{ Storage::url($modele->image) }}" alt="{{ $modele->nom }}" class="w-full h-64 object-cover">
                <div class="p-4 text-center">
                    <h3 class="text-lg font-semibold">{{ $modele->nom }}</h3>
                    <p class="text-gray-600">{{ number_format($modele->prix, 2, ',', ' ') }} €</p>
                    <button wire:click="ajouterAuPanier({{ $modele->id }})" class="bg-blue-500 text-white px-4 py-2 mt-2 rounded-lg">Ajouter au Panier</button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $modeles->links() }}
    </div>
</div>
