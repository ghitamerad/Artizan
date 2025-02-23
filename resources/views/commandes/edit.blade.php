@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Modifier la Commande #{{ $commande->id }}</h2>

    <form action="{{ route('commandes.update', $commande) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="montant_total" class="form-label">Montant Total (€)</label>
            <input type="number" name="montant_total" class="form-control" value="{{ $commande->montant_total }}">
        </div>

        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select name="statut" class="form-control">
                <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="validee" {{ $commande->statut == 'validee' ? 'selected' : '' }}>Validée</option>
                <option value="refusee" {{ $commande->statut == 'refusee' ? 'selected' : '' }}>Refusée</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
</div>
@endsection
