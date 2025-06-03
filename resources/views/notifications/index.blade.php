{{-- resources/views/notifications/index.blade.php --}}

@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Mes notifications</h1>

    <div class="space-y-4">
        @forelse ($notifications as $notification)
            <div class="p-4 bg-white shadow rounded-lg flex justify-between items-center">
                <div>
                    {{ $notification->data['message'] ?? 'Notification' }}
                    <div class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                </div>

                @if (is_null($notification->read_at))
                    <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                        @csrf
                        @method('PATCH')
                        <button class="text-blue-600 text-sm">Marquer comme lue</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-500">Aucune notification.</p>
        @endforelse
    </div>
@endsection
