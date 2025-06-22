<div class="max-w-7xl mx-auto py-10 px-6">
    <!-- Alertes -->
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 mb-6 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 text-red-800 border border-red-300 rounded-lg px-4 py-3 mb-6 flex items-center gap-2">
            <i data-lucide="x-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Carte -->
    <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ $titre }}</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-base text-gray-800">
                <thead class="bg-gray-200 text-gray-700 text-base font-semibold">
                    <tr>
                        <th class="px-6 py-4 text-left">#</th>
                        <th class="px-6 py-4 text-left">Modèle</th>
                        <th class="px-6 py-4 text-center">Quantité</th>
                        <th class="px-6 py-4 text-center">Prix Unitaire</th>
                        <th class="px-6 py-4 text-left">Client</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($commandes as $commande)
                        <tr wire:key="commande-{{ $commande->id }}" class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $commande->modele->nom ?? '—' }}</td>
                            <td class="px-6 py-4 text-center">{{ $commande->quantite }}</td>
                            <td class="px-6 py-4 text-center text-green-600 font-medium">
                                {{ number_format($commande->prix_unitaire, 2) }} DZD
                            </td>
                            <td class="px-6 py-4">{{ $commande->commande->user->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if (is_null($commande->statut) || $commande->statut === 'Null')
                                    <div class="flex justify-center gap-3 flex-wrap">
                                        <button wire:click="accepter({{ $commande->id }})"
                                            wire:loading.attr="disabled"
                                            class="flex items-center gap-1 px-4 py-2 border border-green-200 bg-green-50 text-green-700 rounded-md shadow-sm hover:bg-green-100 transition">
                                            <i data-lucide="check" class="w-4 h-4"></i> Accepter
                                        </button>

                                        <button wire:click="refuser({{ $commande->id }})"
                                            wire:loading.attr="disabled"
                                            class="flex items-center gap-1 px-4 py-2 border border-red-200 bg-red-50 text-red-700 rounded-md shadow-sm hover:bg-red-100 transition">
                                            <i data-lucide="x" class="w-4 h-4"></i> Refuser
                                        </button>
                                    </div>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-white text-sm font-semibold
                                        {{ $commande->statut === 'validee' ? 'bg-green-600' : 'bg-red-600' }}">
                                        {{ ucfirst($commande->statut) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                Aucune commande trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
