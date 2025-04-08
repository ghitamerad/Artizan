<div class="container mx-auto mt-6 p-6">
    <!-- Messages de succès / erreur -->
    <div class="mb-4">
        @if (session()->has('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
                ❌ {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Liste des commandes -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold text-left text-gray-800 mb-6">{{ $titre }}</h2>

        <!-- Ajout de wire:poll pour mise à jour automatique -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200 rounded-lg shadow-sm">
                <thead>
                    <tr class="bg-gray-300 text-gray-800 text-left">
                        <th class="p-4">#</th>
                        <th class="p-4">Modèle</th>
                        <th class="p-4">Quantité</th>
                        <th class="p-4">Prix Unitaire</th>
                        <th class="p-4">Client</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commandes as $commande)
                        <tr wire:key="commande-{{ $commande->id }}" class="border-b hover:bg-gray-100 transition duration-300">
                            <td class="p-4 text-center">{{ $loop->iteration }}</td>
                            <td class="p-4 font-semibold text-gray-700 flex items-center gap-2">
                                <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-md">{{ $commande->modele->nom }}
                            </td>
                            <td class="p-4 text-center">{{ $commande->quantite }}</td>
                            <td class="p-4 font-semibold text-green-600 text-center">
                                {{ number_format($commande->prix_unitaire, 2) }} €
                            </td>
                            <td class="p-4 text-center">{{ $commande->commande->user->name }}</td>
                            <td class="p-4 text-center">
                                @if ($commande->statut === 'Null')
                                    <div class="flex justify-center space-x-3">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-200 flex items-center gap-2"
                                            wire:click="accepter({{ $commande->id }})"
                                            wire:loading.attr="disabled">
                                            ✔ Accepter
                                            </button>
                                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-200 flex items-center gap-2"
                                                wire:click="refuser({{ $commande->id }})"
                                                wire:loading.attr="disabled">
                                                ❌ Refuser
                                            </button>
                                    </div>
                                @else
                                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-lg text-white transition-all
                                        {{ $commande->statut === 'validee' ? 'bg-green-600' : 'bg-red-600' }}">
                                        {{ ucfirst($commande->statut) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Message si aucune commande trouvée -->
        @if($commandes->isEmpty())
            <p class="text-center text-gray-500 mt-6 text-lg">Aucune commande trouvée.</p>
        @endif
    </div>
</div>
