@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Créer une Commande</h2>

    <form action="{{ route('commandes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="montant_total" class="form-label">Montant Total (€)</label>
            <input type="number" name="montant_total" class="form-control" required>
        </div>

        <h4>Modèles</h4>
        <div id="modeles-container">
            <div class="modele-group">
                <input type="number" name="modeles[0][id]" placeholder="ID Modèle" class="form-control mb-2" required>
                <input type="number" name="modeles[0][quantite]" placeholder="Quantité" class="form-control mb-2" required>
                <input type="number" name="modeles[0][prix_unitaire]" placeholder="Prix Unitaire (€)" class="form-control mb-2" required>
            </div>
        </div>

        <button type="button" id="ajouter-modele" class="btn btn-secondary">Ajouter un Modèle</button>

        <button type="submit" class="btn btn-success mt-3">Passer la commande</button>
    </form>
</div>

<script>
    let index = 1;
    document.getElementById('ajouter-modele').addEventListener('click', function () {
        let container = document.getElementById('modeles-container');
        let newGroup = document.createElement('div');
        newGroup.classList.add('modele-group');
        newGroup.innerHTML = `
            <input type="number" name="modeles[${index}][id]" placeholder="ID Modèle" class="form-control mb-2" required>
            <input type="number" name="modeles[${index}][quantite]" placeholder="Quantité" class="form-control mb-2" required>
            <input type="number" name="modeles[${index}][prix_unitaire]" placeholder="Prix Unitaire (€)" class="form-control mb-2" required>
        `;
        container.appendChild(newGroup);
        index++;
    });
</script>
@endsection
