<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User; // Follower sifatida foydalaniladigan model

class NewFollowerNotification extends Notification
{
    use Queueable;

    protected User $follower; // User tipini belgilash

    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    public function via($notifiable)
    {
        return ['database']; // ['mail', 'database'] agar email kerak bo‘lsa
    }

    public function toArray($notifiable)
    {
        return [
            'follower_id' => $this->follower->id,
            'message' => "{$this->follower->name} sizga obuna bo‘ldi!",
            'link' => route('profile.show', ['username' => $this->follower->username]), // Profil sahifasiga yo‘naltirish
        ];
    }
}
