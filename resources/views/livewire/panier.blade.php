<div class="fixed top-0 left-0 w-full bg-white shadow-md p-4 z-50">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-700">🛍️ Votre Panier</h2>
        <button wire:click="togglePanier" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
            {{ $showPanier ? 'Fermer' : 'Afficher' }}
        </button>
    </div>

    @if($showPanier)
        <div class="mt-4">
            @if(count($panier) > 0)
                <ul>
                    @foreach($panier as $item)
                        <li class="flex justify-between items-center border-b py-2">
                            <span class="text-gray-700">{{ $item->modele->nom }}</span>
                            <span class="text-gray-900 font-bold">{{ $item->quantite }}x</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">Votre panier est vide.</p>
            @endif
        </div>
    @endif
</div>
