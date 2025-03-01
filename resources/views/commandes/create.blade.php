@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center text-primary">🛍️ Passer une nouvelle commande</h2>

    <div class="card shadow p-4">
        <form action="{{ route('commandes.store') }}" method="POST">
            @csrf

            <div id="produits-container">
                <div class="produit-item d-flex align-items-center mb-3">
                    <div class="w-50 me-2">
                        <label class="form-label fw-bold">Modèle</label>
                        <select class="form-control" name="modeles[0][id]" required>
                            @foreach ($modeles as $modele)
                                <option value="{{ $modele->id }}">
                                    {{ $modele->nom }} - {{ number_format($modele->prix, 2) }} €
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-25 me-2">
                        <label class="form-label fw-bold">Quantité</label>
                        <input type="number" class="form-control" name="modeles[0][quantite]" min="1" required>
                    </div>

                    <button type="button" class="btn btn-danger remove-product d-none">❌</button>
                </div>
            </div>

            <button type="button" class="btn btn-success mb-3" id="add-product">➕ Ajouter un produit</button>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">✅ Valider la commande</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let index = 1;

    document.getElementById('add-product').addEventListener('click', function() {
        let container = document.getElementById('produits-container');
        let newProduct = document.createElement('div');
        newProduct.classList.add('produit-item', 'd-flex', 'align-items-center', 'mb-3');
        newProduct.innerHTML = `
            <div class="w-50 me-2">
                <label class="form-label fw-bold">Modèle</label>
                <select class="form-control" name="modeles[${index}][id]" required>
                    @foreach ($modeles as $modele)
                        <option value="{{ $modele->id }}">{{ $modele->nom }} - {{ number_format($modele->prix, 2) }} €</option>
                    @endforeach
                </select>
            </div>
            <div class="w-25 me-2">
                <label class="form-label fw-bold">Quantité</label>
                <input type="number" class="form-control" name="modeles[${index}][quantite]" min="1" required>
            </div>
            <button type="button" class="btn btn-danger remove-product">❌</button>
        `;

        container.appendChild(newProduct);
        index++;

        updateRemoveButtons();
    });

    document.getElementById('produits-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            e.target.parentElement.remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        let buttons = document.querySelectorAll('.remove-product');
        buttons.forEach(button => {
            button.classList.toggle('d-none', buttons.length === 1);
        });
    }

    updateRemoveButtons();
});//
</script>
@endsection
