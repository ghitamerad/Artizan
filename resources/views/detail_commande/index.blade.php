@extends('layouts.test2')

@section('content')

<div class="container mx-auto p-6 mt-10">
    <h2 class="text-3xl font-bold text-gray-700 mb-6 flex items-center gap-2">
        <i data-lucide="shopping-bag" class="w-7 h-7 text-gray-700"></i>
        Mes Commandes
    </h2>

    <!-- Commandes en cours -->
    <div class="bg-white p-6 rounded-2xl shadow-lg mb-10 border border-[#F5F5DC]">
        <h3 class="text-xl font-semibold text-[#2C3E50] mb-4 flex items-center gap-2">
            <i data-lucide="loader-circle" class="w-5 h-5 text-yellow-500"></i>
            Commandes en cours
        </h3>
        @if ($commandesEnCours->isEmpty())
            <p class="text-gray-500 italic">Aucune commande en cours.</p>
        @else
            <div class="space-y-4">
                @foreach ($commandesEnCours as $commande)
                    <div class="p-4 border border-[#D4AF37]/30 bg-[#FDFCF8] rounded-xl flex justify-between items-center hover:shadow transition">
                        <div>
                            <p class="font-medium text-[#2C3E50]">
                                Commande #{{ $commande->id }} - {{ $commande->created_at->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-gray-500">Statut :
                                <span class="px-2 py-1 text-white text-xs rounded-full
                                    {{ $commande->statut == 'en_attente' ? 'bg-yellow-500' : 'bg-green-600' }}">
                                    {{ ucfirst($commande->statut) }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('detail-commandes.showClient', $commande->id) }}"
                           class="px-4 py-2 bg-[#D4AF37] text-white text-sm rounded-lg hover:bg-[#C19B2C] transition duration-200 inline-flex items-center gap-1">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            Voir détails
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Commandes précédentes -->
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-[#F5F5DC]">
        <h3 class="text-xl font-semibold text-[#2C3E50] mb-4 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            Commandes terminées
        </h3>
        @if ($commandesPrecedentes->isEmpty())
            <p class="text-gray-500 italic">Aucune commande terminée.</p>
        @else
            <div class="space-y-4">
                @foreach ($commandesPrecedentes as $commande)
                    <div class="p-4 border border-gray-200 bg-gray-50 rounded-xl flex justify-between items-center hover:shadow transition">
                        <div>
                            <p class="font-medium text-[#2C3E50]">
                                Commande #{{ $commande->id }} - {{ $commande->created_at->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Montant :
                                <span class="font-semibold text-[#D4AF37]">{{ number_format($commande->montant_total, 2) }} €</span>
                            </p>
                        </div>
                        <a href="{{ route('detail-commandes.showClient', $commande->id) }}"
                           class="px-4 py-2 bg-[#2C3E50] text-white text-sm rounded-lg hover:bg-[#1A252F] transition duration-200 inline-flex items-center gap-1">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            Voir détails
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <a href="{{ route('panier') }}"
       class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
        Voir mon panier
    </a>
</div>

@endsection
