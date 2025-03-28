<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-700 mb-6">Votre Panier</h1>

    @if (empty($panier))
        <p class="text-gray-500 text-lg">Votre panier est vide.</p>
    @else
        <div class="bg-white shadow-lg rounded-lg p-6">
            <ul class="space-y-4">
                @foreach ($panier as $item)
                    <li class="flex items-center justify-between p-4 border rounded-lg bg-gray-50">
                        <div class="flex items-center space-x-4">
                            <img src="" alt="Modèle" class="w-16 h-16 rounded-lg object-cover">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $item['nom'] }}</h2>
                                <p class="text-gray-600">Quantité : {{ $item['quantite'] }}</p>

                                @if (!empty($item['mesures']))
                                    <div class="relative inline-block mt-2">
                                        <button class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">
                                            Voir les mesures
                                        </button>
                                        <div
                                            class="absolute hidden group-hover:flex hover:flex flex-col bg-white border shadow-xl p-4 rounded-lg z-10 w-56 text-sm text-gray-700 top-10 left-0">
                                            <h3 class="font-semibold mb-2">Mesures personnalisées :</h3>
                                            <ul class="list-disc list-inside">
                                                @foreach ($item['mesures'] as $key => $value)
                                                <li>{{ ucfirst($key) }} : {{ $value }} cm</li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <style>
                                        .relative:hover>div,
                                        .relative:focus-within>div {
                                            display: flex !important;
                                        }
                                    </style>
                                @endif
                            </div>

                            <button wire:click="retirerDuPanier({{ $item['id'] }})"
                                class="text-red-500 hover:text-red-700">
                                ❌ Retirer
                            </button>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 flex justify-between items-center">
                <p class="text-lg font-semibold text-gray-700">Total articles : {{ $totalArticles }}</p>
                <div class="space-x-4">
                    <form action="{{ route('commandes.store') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                            Commander
                        </button>
                    </form>
                    <button wire:click="viderPanier"
                        class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        Vider le panier
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
