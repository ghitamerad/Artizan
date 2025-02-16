<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
</head>
<body>
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); text-align: center;">
        <h2>Tableau de Bord</h2>
        <p>Bienvenue sur votre tableau de bord.</p>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Déconnexion</button>
        </form>
    </div>
</body>
</html>
