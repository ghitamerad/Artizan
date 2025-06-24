@extends('layouts.test2')

@section('content')
<div class="container mx-auto p-6 mt-10 space-y-8 text-[#2C3E50]">

    {{-- 🔹 Titre --}}
    <div class="flex items-center gap-2 text-2xl font-bold">
        <i data-lucide="file-text" class="w-6 h-6 text-[#2C3E50]"></i>
        <h2>Détails de la commande #{{ $commande->id }}</h2>
    </div>

    {{-- 🔹 Infos commande --}}
    <div class="bg-white p-6 rounded-2xl shadow space-y-3 text-sm">
        <div class="flex items-center gap-2">
            <i data-lucide="calendar" class="w-4 h-4 text-gray-500"></i>
            <p>Date : <span class="font-semibold">{{ $commande->created_at->format('d/m/Y') }}</span></p>
        </div>

        <div class="flex items-center gap-2">
            <i data-lucide="badge-check" class="w-4 h-4 text-gray-500"></i>
            <p>
                Statut :
                <span class="px-2 py-1 text-white text-xs rounded-full
                    {{ $commande->statut === 'en_attente' ? 'bg-yellow-500' : ($commande->statut === 'validee' ? 'bg-green-500' : 'bg-gray-500') }}">
                    {{ ucfirst($commande->statut) }}
                </span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <i data-lucide="credit-card" class="w-4 h-4 text-gray-500"></i>
            <p>Montant total : <span class="font-semibold">{{ number_format($commande->montant_total, 2) }} €</span></p>
        </div>
    </div>

    {{-- 🔹 Articles --}}
    <div>
        <div class="flex items-center gap-2 text-lg font-semibold mb-4">
            <i data-lucide="shopping-bag" class="w-5 h-5 text-[#2C3E50]"></i>
            <h3>Articles commandés</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow space-y-4">
            @foreach ($detailsCommande as $detail)
                <div class="p-4 border border-gray-200 rounded-lg space-y-1 text-sm">
                    <p><span class="font-medium">Modèle :</span> #{{ $detail->modele_id }}</p>
                    <p class="text-gray-600">Quantité : {{ $detail->quantite }}</p>
                    <p class="text-gray-600">Prix unitaire : {{ number_format($detail->prix_unitaire, 2) }} €</p>
                    <p class="text-gray-600">
                        Customisé :
                        <span class="font-semibold {{ $detail->custom ? 'text-green-600' : 'text-gray-600' }}">
                            {{ $detail->custom ? 'Oui' : 'Non' }}
                        </span>
                    </p>
                    @php
                        $statut = $detail->statut;
                        $color = match($statut) {
                            'fini' => 'text-green-600',
                            'validee', 'Null', null => 'text-gray-500',
                            default => 'text-gray-500'
                        };
                    @endphp
                    <p class="{{ $color }}">Statut : {{ $statut ?? 'en cours' }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 🔹 Bouton retour --}}
    <a href="{{ route('detail-commandes.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-[#2C3E50] text-white text-sm rounded-lg hover:bg-[#1A2530] transition">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Retour à mes commandes
    </a>
</div>

{{-- Lucide Icons --}}
@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        document.addEventListener("livewire:load", () => {
            Livewire.hook('message.processed', () => {
                lucide.createIcons();
            });
        });
    </script>
@endpush
@endsection
