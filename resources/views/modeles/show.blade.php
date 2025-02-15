@extends('layouts.app')

@section('content')
<div>
    <h1>{{ $modele->nom }}</h1>
    <p>Description : {{ $modele->description }}</p>
    <p>Prix : {{ $modele->prix }} €</p>
    <p>Catégorie : {{ $modele->categorie->nom }}</p>

    @if($modele->patron)
        <p><a href="{{ asset('storage/' . $modele->patron) }}" download>Télécharger le patron</a></p>
    @endif

    @if($modele->xml)
        <p><a href="{{ asset('storage/' . $modele->xml) }}" download>Télécharger le fichier XML</a></p>
    @endif

    <a href="{{ route('modeles.index') }}">Retour</a>

    @can('update', $modele)
        <a href="{{ route('modeles.edit', $modele) }}">Modifier</a>
    @endcan

    @can('delete', $modele)
        <form action="{{ route('modeles.destroy', $modele) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
        </form>
    @endcan
</div>
@endsection
