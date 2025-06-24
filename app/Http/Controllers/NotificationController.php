<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\User;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Autoriser seulement les rôles admin, gérante et couturière
        if (!in_array($user->role, ['admin', 'gerante', 'couturiere'])) {
            abort(403, 'Accès non autorisé');
        }


        $notificationsNonLues = $user->unreadNotifications;
        $notificationsLues = $user->readNotifications;

        return view('notifications.index', compact('notificationsNonLues', 'notificationsLues'));
    }

    public function indexClient()
    {
        $user = Auth::user();

        if ($user->role !== 'client') {
            abort(403, 'Accès non autorisé');
        }

        $notifications = $user->notifications;

        $notificationsNonLues = $user->unreadNotifications;
        $notificationsLues = $user->readNotifications;

        return view('notifications.client', compact('notificationsNonLues', 'notificationsLues'));
    }


    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications->findOrFail($id);
        $notification->markAsRead();

        return back();
    }
}
