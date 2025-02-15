@extends('layouts.app')

@section('content')
<div>
    <h1>Liste des Modèles</h1>

    @if(session()->has('message'))
        <p>{{ session('message') }}</p>
    @endif

    @can('create', App\Models\Modele::class)
        <a href="{{ route('modeles.create') }}">Ajouter un Modèle</a>
    @endcan

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prix</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modeles as $modele)
                <tr>
                    <td>{{ $modele->nom }}</td>
                    <td>{{ $modele->prix }} €</td>
                    <td>
                        <a href="{{ route('modeles.show', $modele) }}">Voir</a>
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
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
