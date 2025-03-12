
<div class="container mx-auto mt-6 p-6">
    <!-- Liste des commandes -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-center text-blue-700 mb-6">{{ $titre }}</h2>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200 rounded-lg shadow-sm">
                <thead>
                    <tr class="bg-blue-600 text-white text-left">
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
                        <tr class="border-b hover:bg-gray-100 transition duration-200">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-semibold text-gray-700">{{ $commande->modele->nom }} {{$commande->modele->id}}</td>
                            <td class="p-4">{{ $commande->quantite }}</td>
                            <td class="p-4 font-semibold text-green-600">{{ number_format($commande->prix_unitaire, 2) }} €</td>
                            <td class="p-4">{{ $commande->commande->user->name }}</td>
                            <td class="p-4 text-center">
                                @if ($commande->statut === 'Null')
                                    <div class="flex justify-center space-x-3">
                                        <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-200 flex items-center gap-2"
                                            wire:click="accepter({{ $commande->id }})">
                                            ✔ Accepter
                                        </button>
                                        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-200 flex items-center gap-2"
                                            wire:click="refuser({{ $commande->id }})">
                                            ❌ Refuser
                                        </button>
                                    </div>
                                @else
                                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-lg text-white
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

        @if($commandes->isEmpty())
            <p class="text-center text-gray-500 mt-6">Aucune commande trouvée.</p>
        @endif
    </div>
</div>
