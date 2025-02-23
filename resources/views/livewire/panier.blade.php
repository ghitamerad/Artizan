@extends('layouts.app')

@section('content')
@livewire('panier')

<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Mon Panier</h2>

    @if(count($panier) > 0)
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Modèle</th>
                    <th class="border p-2">Quantité</th>
                    <th class="border p-2">Prix Unitaire</th>
                    <th class="border p-2">Total</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($panier as $modeleId => $item)
                    <tr>
                        <td class="border p-2">{{ $item['nom'] }}</td>
                        <td class="border p-2">
                            <input type="number" min="1" wire:change="mettreAJourQuantite({{ $modeleId }}, $event.target.value)" value="{{ $item['quantite'] }}" class="w-16 border p-1">
                        </td>
                        <td class="border p-2">{{ number_format($item['prix_unitaire'], 2) }} €</td>
                        <td class="border p-2">{{ number_format($item['quantite'] * $item['prix_unitaire'], 2) }} €</td>
                        <td class="border p-2">
                            <button wire:click="supprimerDuPanier({{ $modeleId }})" class="bg-red-500 text-white px-4 py-2 rounded">
                                Retirer
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <h3 class="text-lg font-semibold">Total : {{ number_format($total, 2) }} €</h3>
            <button wire:click="passerCommande" class="bg-green-500 text-white px-4 py-2 rounded">Passer la commande</button>
        </div>
    @else
        <p class="text-gray-500">Votre panier est vide.</p>
    @endif
</div>
@endsection
