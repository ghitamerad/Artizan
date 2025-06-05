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
$user = User::findOrFail(Auth::id());
    $notifications = $user->notifications;

    return view('notifications.index', compact('notifications'));
}

    public function markAsRead($id)
{
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    return back();
}
}
