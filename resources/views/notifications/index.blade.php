@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Mes notifications</h1>

    {{-- 🔔 Notifications non lues --}}
    <div class="mb-10">
    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2 text-blue-700">
        <i data-lucide="bell-ring" class="w-5 h-5"></i>
        Notifications non lues
    </h2>

        @forelse ($notificationsNonLues as $notification)
            <div class="p-4 bg-white shadow rounded-lg flex justify-between items-center">
                <div>
                    {{ $notification->data['message'] ?? 'Notification' }}
                    <div class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                </div>

                <div class="flex items-center gap-4">
                    @if (!empty($notification->data['lien']))
                        <a href="{{ $notification->data['lien'] }}"
                            class="flex items-center gap-1 px-3 py-1 border border-green-200 bg-green-50 text-green-700 rounded-md shadow-sm hover:bg-green-100 transition text-sm">
                            <i data-lucide="eye" class="w-4 h-4"></i> Voir
                        </a>
                    @endif

                    <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-1 px-3 py-1 border border-blue-200 bg-blue-50 text-blue-700 rounded-md shadow-sm hover:bg-blue-100 transition text-sm">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Marquer comme lue
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Aucune nouvelle notification.</p>
        @endforelse
    </div>

    {{-- ✅ Notifications lues --}}
    <div>
        <h2 class="text-lg font-semibold flex items-center gap-2 mb-4 text-gray-600">        <i data-lucide="bell" class="w-5 h-5"></i>
 Notifications lues</h2>

        @forelse ($notificationsLues as $notification)
            <div class="p-4 bg-gray-50 border rounded-lg shadow-sm flex justify-between items-center">
                <div>
                    {{ $notification->data['message'] ?? 'Notification' }}
                    <div class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                </div>

                @if (!empty($notification->data['lien']))
                    <a href="{{ $notification->data['lien'] }}"
                        class="flex items-center gap-1 px-3 py-1 border border-green-100 bg-green-50 text-green-600 rounded-md shadow-sm hover:bg-green-100 transition text-sm">
                        <i data-lucide="eye" class="w-4 h-4"></i> Voir
                    </a>
                @endif
            </div>
        @empty
            <p class="text-gray-400">Aucune notification lue.</p>
        @endforelse
    </div>
@endsection
