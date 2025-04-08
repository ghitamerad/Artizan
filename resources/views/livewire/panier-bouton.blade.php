<div x-data="{ open: false }" class="relative">
    <!-- Bouton du panier -->
    <button @click="open = !open" class="p-2 bg-[#D4AF37] text-white rounded-full shadow-lg hover:bg-[#C19B2C] transition duration-300 ease-in-out">
        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l1 5m0 0h13l1-5h2m-2 5l-1 9H6L5 8m0 0H3m7 13a2 2 0 100-4 2 2 0 000 4zm7 0a2 2 0 100-4 2 2 0 000 4z" />
        </svg>
        <!-- Badge du nombre d'articles -->
        @if ($totalArticles > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                {{ $totalArticles }}
            </span>
        @endif
    </button>

    <!-- Dropdown Panier -->
    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg">
        <div class="p-4">
            <h3 class="text-lg font-semibold text-[#2C3E50]">Panier</h3>
            <ul class="mt-2 space-y-2">
                @forelse ($panier as $item)
                    <li class="text-sm text-gray-700">{{ $item['nom'] }}</li>
                @empty
                    <li class="text-sm text-gray-500">Votre panier est vide.</li>
                @endforelse
            </ul>
            <a href="{{ route('panier') }}" class="mt-4 w-full bg-[#D4AF37] text-white py-1 px-2 rounded-lg hover:bg-[#C19B2C] block text-center">
                Afficher
            </a>
        </div>
    </div>
</div>
