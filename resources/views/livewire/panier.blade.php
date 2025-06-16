<div>
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold text-gray-800 mb-8 flex items-center gap-2">
            <i data-lucide="shopping-cart" class="w-10 h-10 text-gray-800"></i>
            Votre Panier
        </h1>

        @if (empty($panier))
            <p class="text-gray-500 text-xl">Votre panier est vide.</p>
        @else
            <div class="bg-white shadow-xl rounded-xl p-6">
                <ul class="space-y-6">
                    @foreach ($panier as $item)
                        <li class="flex items-start gap-6 p-4 border rounded-xl bg-gray-50">
                            {{-- Image du modèle --}}
                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/default.jpg') }}"
                                 alt="Modèle"
                                 class="w-28 h-28 rounded-lg object-cover border shadow-sm">

                            {{-- Infos --}}
                            <div class="flex-1 space-y-2">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $item['nom'] }}</h2>
                                <p class="text-gray-600 text-base">Quantité : <strong>{{ $item['quantite'] }}</strong></p>

                                {{-- Mesures personnalisées --}}
                                @if (!empty($item['mesures']))
                                    <div class="relative group">
                                        <a href="{{ route('modeles.mesures', ['modele' => $item['id']]) }}"
                                           class="inline-flex items-center gap-1 text-base text-blue-600 hover:underline">
                                            <i data-lucide="ruler" class="w-4 h-4"></i>
                                            Voir les mesures
                                        </a>

                                        <div
                                            class="absolute left-0 mt-2 hidden group-hover:flex bg-white border shadow-xl p-4 rounded-lg z-10 w-64 text-sm text-gray-800">
                                            <div>
                                                <h3 class="font-semibold mb-2 text-base">Mesures personnalisées :</h3>
                                                <ul class="list-disc list-inside space-y-1 text-[15px]">
                                                    @foreach ($item['mesures'] as $key => $value)
                                                        <li><span class="font-medium">{{ ucfirst($key) }}</span> : {{ $value }} cm</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Bouton supprimer --}}
                            <button wire:click="retirerDuPanier({{ $item['id'] }})"
                                    class="text-red-500 hover:text-red-700 transition mt-1"
                                    title="Retirer l'article">
                                <i data-lucide="trash-2" class="w-6 h-6"></i>
                            </button>
                        </li>
                    @endforeach
                </ul>

                {{-- Résumé et actions --}}
                <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-xl font-semibold text-gray-700">
                        Total articles : <span class="text-blue-600">{{ $totalArticles }}</span>
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <form action="{{ route('commandes.store') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                Commander
                            </button>
                        </form>

                        <button wire:click="viderPanier"
                                class="inline-flex items-center gap-2 bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                            Vider le panier
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Lien vers les commandes --}}
        <a href="{{ route('detail-commandes.index') }}"
           class="mt-6 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
            <i data-lucide="list" class="w-4 h-4"></i>
            Voir mes commandes
        </a>

        <a href="{{ route('home') }}"
           class="mt-6 inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white text-sm rounded-lg hover:bg-indigo-600 transition">
            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
            Continuer vos achats
        </a>
    </div>
</div>
