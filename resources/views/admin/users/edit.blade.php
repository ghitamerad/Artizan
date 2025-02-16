<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Utilisateur</title>
</head>
<body>
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); text-align: center;">
        <h2>Modifier un Utilisateur</h2>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table align="center" width="100%" cellpadding="10">
                <tr>
                    <td align="right"><label for="name">Nom :</label></td>
                    <td><input type="text" name="name" value="{{ $user->name }}" required></td>
                </tr>
                <tr>
                    <td align="right"><label for="email">Email :</label></td>
                    <td><input type="email" name="email" value="{{ $user->email }}" required></td>
                </tr>
                <tr>
                    <td align="right"><label for="role">Rôle :</label></td>
                    <td>
                        <select name="role" required>
                            <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            <option value="gerante" {{ $user->role == 'gerante' ? 'selected' : '' }}>Gérante</option>
                            <option value="couturiere" {{ $user->role == 'couturiere' ? 'selected' : '' }}>Couturière</option>
                        </select>
                    </td>
                </tr>
            </table>

            <br>
            <button type="submit">Enregistrer</button>
            <a href="{{ route('admin.users.index') }}">Annuler</a>
        </form>
    </div>
</body>
</html>
