@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier la valeur : {{ $valeur->nom }}</h2>

    <form action="{{ route('valeurs.update', $valeur) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $valeur->nom) }}" required>
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        @if($valeur->image)
            <div class="mb-3">
                <label class="form-label">Image actuelle :</label><br>
                <img src="{{ asset('storage/' . $valeur->image) }}" alt="Image actuelle" width="150">
            </div>
        @endif

        <div class="mb-3">
            <label for="image" class="form-label">Changer l'image (optionnel)</label>
            <input type="file" name="image" id="image" class="form-control">
            @error('image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="custom" id="custom" class="form-check-input" {{ old('custom', $valeur->custom) ? 'checked' : '' }}>
            <label for="custom" class="form-check-label">Personnalisable ?</label>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('attributs.show', $valeur->attribut_id) }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
