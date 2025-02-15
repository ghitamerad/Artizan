<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Extraction Mesures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="mb-4">Tester l'extraction des mesures d'un fichier .vit</h2>

    @if(session('mesures'))
        <h4>Résultats :</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de la mesure</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('mesures') as $mesure)
                    <tr>
                        <td>{{ $mesure['nom'] }}</td>
                        <td>{{ $mesure['valeur'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <form action="{{ route('test.extraction') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="fichier" class="form-label">Choisissez un fichier .vit :</label>
            <input type="file" class="form-control" name="fichier" required>
        </div>
        <button type="submit" class="btn btn-primary">Extraire</button>
    </form>

</body>
</html>
