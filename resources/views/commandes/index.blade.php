@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Liste des Commandes</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Montant Total</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commandes as $commande)
            <tr>
                <td>{{ $commande->id }}</td>
                <td>{{ $commande->user->name }}</td>
                <td>{{ $commande->montant_total }} €</td>
                <td>{{ ucfirst($commande->statut) }}</td>
                <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('commandes.show', $commande) }}" class="btn btn-info btn-sm">Voir</a>

                    @can('update', $commande)
                        <a href="{{ route('commandes.edit', $commande) }}" class="btn btn-primary btn-sm">Modifier</a>
                    @endcan

                    @can('delete', $commande)
                        <form action="{{ route('commandes.destroy', $commande) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette commande ?')">Supprimer</button>
                        </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
