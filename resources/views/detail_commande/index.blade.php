@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">📦 Mes Commandes</h2>

    <!-- Commandes en cours -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-lg font-semibold text-blue-600 mb-3">🚀 Commandes en cours</h3>
        @if ($commandesEnCours->isEmpty())
            <p class="text-gray-500">Aucune commande en cours.</p>
        @else
            <div class="space-y-4">
                @foreach ($commandesEnCours as $commande)
                    <div class="p-4 border rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-medium">Commande #{{ $commande->id }} - {{ $commande->created_at->format('d/m/Y') }}</p>
                            <p class="text-sm text-gray-500">Statut :
                                <span class="px-2 py-1 text-white text-xs rounded-full
                                    {{ $commande->statut == 'en_attente' ? 'bg-yellow-500' : 'bg-green-500' }}">
                                    {{ ucfirst($commande->statut) }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('detail-commandes.showClient', $commande->id) }}"
                           class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                            Voir détails
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Commandes précédentes -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-green-600 mb-3">✅ Commandes terminées</h3>
        @if ($commandesPrecedentes->isEmpty())
            <p class="text-gray-500">Aucune commande terminée.</p>
        @else
            <div class="space-y-4">
                @foreach ($commandesPrecedentes as $commande)
                    <div class="p-4 border rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-medium">Commande #{{ $commande->id }} - {{ $commande->created_at->format('d/m/Y') }}</p>
                            <p class="text-sm text-gray-500">Montant : <span class="font-semibold">{{ number_format($commande->montant_total, 2) }} €</span></p>
                        </div>
                        <a href="{{ route('detail-commandes.show', $commande->id) }}"
                           class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition">
                            Voir détails
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
