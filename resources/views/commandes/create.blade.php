@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Passer une nouvelle commande</h2>

    <form action="{{ route('commandes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="modele_id" class="form-label">Modèle</label>
            <select class="form-control" name="modele_id" required>
                @foreach ($modeles as $modele)
                    <option value="{{ $modele->id }}">{{ $modele->nom }} - {{ number_format($modele->prix, 2) }} €</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantite" class="form-label">Quantité</label>
            <input type="number" class="form-control" name="quantite" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Valider la commande</button>
    </form>
</div>
@endsection
