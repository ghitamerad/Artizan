@extends('layouts.app')

@section('content')
<div>
    <h1>Modifier le Modèle</h1>

    <form action="{{ route('modeles.update', $modele) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label>Nom du modèle</label>
            <input type="text" name="nom" value="{{ old('nom', $modele->nom) }}">
            @error('nom') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Description</label>
            <textarea name="description">{{ old('description', $modele->description) }}</textarea>
        </div>

        <div>
            <label>Prix</label>
            <input type="number" name="prix" value="{{ old('prix', $modele->prix) }}">
            @error('prix') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Catégorie</label>
            <select name="categorie_id">
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ $modele->categorie_id == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
            @error('categorie_id') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Fichier .val (Patron)</label>
            <input type="file" name="patron">
            @error('patron') <span class="error">{{ $message }}</span> @enderror
            @if($modele->patron)
                <p>Fichier actuel : <a href="{{ asset('storage/' . $modele->patron) }}" download>Télécharger</a></p>
            @endif
        </div>

        <div>
            <label>Fichier .vit (Mesures XML)</label>
            <input type="file" name="xml">
            @error('xml') <span class="error">{{ $message }}</span> @enderror
            @if($modele->xml)
                <p>Fichier actuel : <a href="{{ asset('storage/' . $modele->xml) }}" download>Télécharger</a></p>
            @endif
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection
