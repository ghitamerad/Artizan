<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Modèles</title>
</head>
<body>
    <div style="max-width: 900px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); text-align: center;">
        <h2>Bienvenue sur notre boutique</h2>

        <!-- Barre de Recherche -->
        <form method="GET" action="">
            <table align="center" width="100%" cellpadding="10">
                <tr>
                    <td align="right"><label for="search">Rechercher :</label></td>
                    <td><input type="text" name="search" id="search" placeholder="Rechercher un modèle..."></td>
                    <td><button type="submit">Rechercher</button></td>
                </tr>
            </table>
        </form>

        <br>

        <!-- Tableau des modèles -->
        <table border="1" width="100%" cellpadding="10">
            <tr>
                <th>Image</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Action</th>
            </tr>

            @foreach($modeles as $modele)
            <tr align="center">
                <td>
                    @if($modele->image)
                        <img src="{{ Storage::url($modele->image) }}" alt="{{ $modele->nom }}" width="100">
                    @else
                        <span>Pas d'image</span>
                    @endif
                </td>
                <td>{{ $modele->nom }}</td>
                <td>{{ $modele->categorie->nom }}</td>
                <td>{{ number_format($modele->prix, 2, ',', ' ') }} €</td>
                <td>
                    <a href="{{ route('modele.show', $modele->id) }}">Voir</a> | 
                    <form action="{{ route('ajouter.au.panier', $modele->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Ajouter au panier</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>

        <br>

        <!-- Pagination -->
        <div>
            {{ $modeles->links() }}
        </div>
    </div>
</body>
</html>
