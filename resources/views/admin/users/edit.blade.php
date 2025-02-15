@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Modifier l'utilisateur</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Client</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="gerante" {{ $user->role == 'gerante' ? 'selected' : '' }}>Gérante</option>
                    <option value="couturiere" {{ $user->role == 'couturiere' ? 'selected' : '' }}>Couturière</option>
                </select>
            </div>


            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
