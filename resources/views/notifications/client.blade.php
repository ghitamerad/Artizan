@extends('layouts.test2')

@section('content')
<div class="container mx-auto p-6 mt-10 space-y-8 text-[#2C3E50]">

    {{-- 🔔 Titre --}}
    <div class="flex items-center gap-2 text-2xl font-bold mb-6">
        <i data-lucide="bell" class="w-6 h-6 text-[#2C3E50]"></i>
        <h2>Mes notifications</h2>
    </div>

    {{-- 🔄 Non lues --}}
    @if ($notificationsNonLues->count())
        <div>
            <h3 class="text-lg font-semibold text-blue-700 flex items-center gap-2 mb-3">
                <i data-lucide="bell-ring" class="w-5 h-5"></i> Notifications non lues
            </h3>
            <div class="space-y-4">
                @foreach ($notificationsNonLues as $notification)
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg shadow flex justify-between items-center">
                        <div>
                            <p class="text-sm">{{ $notification->data['message'] ?? 'Notification' }}</p>
                            <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            @if (!empty($notification->data['lien']))
                                <a href="{{ $notification->data['lien'] }}"
                                    class="flex items-center gap-1 px-3 py-1 border border-green-200 bg-green-50 text-green-700 rounded-md shadow-sm hover:bg-green-100 transition text-xs">
                                    <i data-lucide="eye" class="w-4 h-4"></i> Voir
                                </a>
                            @endif

                            <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-1 px-3 py-1 border border-blue-200 bg-white text-blue-700 rounded-md shadow-sm hover:bg-blue-100 transition text-xs">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Marquer comme lue
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- 📁 Notifications lues --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2 mt-8 mb-3">
            <i data-lucide="archive" class="w-5 h-5"></i> Notifications lues
        </h3>
        @forelse ($notificationsLues as $notification)
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow flex justify-between items-center">
                <div>
                    <p class="text-sm">{{ $notification->data['message'] ?? 'Notification' }}</p>
                    <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                </div>

                @if (!empty($notification->data['lien']))
                    <a href="{{ $notification->data['lien'] }}"
                        class="flex items-center gap-1 px-3 py-1 border border-green-200 bg-green-50 text-green-700 rounded-md shadow-sm hover:bg-green-100 transition text-xs">
                        <i data-lucide="eye" class="w-4 h-4"></i> Voir
                    </a>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-sm">Aucune notification lue.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
    document.addEventListener("livewire:load", () => {
        Livewire.hook('message.processed', () => {
            lucide.createIcons();
        });
    });
</script>
@endpush
