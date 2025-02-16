<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
</head>
<body>
    <div style="max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); text-align: center;">
        <h2>Liste des Utilisateurs</h2>

        <table border="1" width="100%" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            @foreach($users as $user)
                <tr align="center">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}">Modifier</a> |
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
