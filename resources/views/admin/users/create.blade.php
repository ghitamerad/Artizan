<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur</title>
</head>
<body>
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); text-align: center;">
        <h2>Ajouter un Utilisateur</h2>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <table align="center" width="100%" cellpadding="10">
                <tr>
                    <td align="right"><label for="name">Nom :</label></td>
                    <td><input type="text" name="name" required></td>
                </tr>
                <tr>
                    <td align="right"><label for="email">Email :</label></td>
                    <td><input type="email" name="email" required></td>
                </tr>
                <tr>
                    <td align="right"><label for="password">Mot de passe :</label></td>
                    <td><input type="password" name="password" required></td>
                </tr>
                <tr>
                    <td align="right"><label for="password_confirmation">Confirmer :</label></td>
                    <td><input type="password" name="password_confirmation" required></td>
                </tr>
                <tr>
                    <td align="right"><label for="role">Rôle :</label></td>
                    <td>
                        <select name="role" required>
                            <option value="client">Client</option>
                            <option value="admin">Administrateur</option>
                            <option value="gerante">Gérante</option>
                            <option value="couturiere">Couturière</option>
                        </select>
                    </td>
                </tr>
            </table>

            <br>
            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>
