<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // O‘qilmagan bildirishnomalarni "o‘qilgan" deb belgilash
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', [
            'notifications' => $user->notifications()->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['link'] ?? route('notifications.index'));
    }
}
