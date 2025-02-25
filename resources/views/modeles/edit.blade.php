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

    <h2>Mesures du Modèle</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Label</th>
                <th>Valeur par défaut</th>
                <th>Variable XML</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modele->mesures as $mesure)
                <tr>
                    <td>{{ $mesure->label }}</td>
                    <td>{{ $mesure->valeur_par_defaut }}</td>
                    <td>{{ $mesure->variable_xml }}</td>
                    <td>
                        <a href="{{ route('mesures.edit', $mesure) }}">Modifier</a>
                        <form action="{{ route('mesures.destroy', $mesure) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Ajouter une nouvelle mesure</h3>
    <form action="{{ route('mesures.store') }}" method="POST">
        @csrf
        <input type="hidden" name="modele_id" value="{{ $modele->id }}">

        <div>
            <label>Label</label>
            <input type="text" name="label" required>
        </div>

        <div>
            <label>Valeur par défaut</label>
            <input type="number" name="valeur_par_defaut" step="0.01" required>
        </div>

        <div>
            <label>Variable XML</label>
            <input type="text" name="variable_xml" required>
        </div>

        <button type="submit">Ajouter</button>
    </form>
</div>
@endsection
