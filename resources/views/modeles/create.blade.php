@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Créer un nouveau modèle</h2>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('modeles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nom du modèle -->
        <div class="mb-3">
            <label for="nom" class="form-label">Nom du modèle</label>
            <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <!-- Prix -->
        <div class="mb-3">
            <label for="prix" class="form-label">Prix (en €)</label>
            <input type="number" class="form-control" id="prix" name="prix" min="0" value="{{ old('prix') }}" required>
        </div>

        <!-- Catégorie -->
        <div class="mb-3">
            <label for="categorie_id" class="form-label">Catégorie</label>
            <select class="form-control" id="categorie_id" name="categorie_id" required>
                <option value="">Sélectionner une catégorie</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Upload du patron (.val) -->
        <div class="mb-3">
            <label for="patron" class="form-label">Fichier Patron (.val)</label>
            <input type="file" class="form-control" id="patron" name="patron" accept=".val" required>
        </div>

        <!-- Upload du fichier de mesures (.xml ou .vit) -->
        <div class="mb-3">
            <label for="xml" class="form-label">Fichier de Mesures (.xml ou .vit)</label>
            <input type="file" class="form-control" id="xml" name="xml" accept=".xml,.vit" required>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary">Créer le modèle</button>
        <a href="{{ route('modeles.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
