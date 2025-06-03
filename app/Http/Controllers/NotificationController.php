<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use App\models\User;

class NotificationController extends Controller
{
    public function index()
    {
/** @var \App\Models\User $user */
        $notifications = auth()->user()->notifications;

        return view('notifications.index', compact('notifications'));
    }
    public function markAsRead($id)
{
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    return back();
}
}
