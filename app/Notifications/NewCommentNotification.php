<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Comment;

class NewCommentNotification extends Notification
{
    use Queueable;

    public $comment;

    public function __construct(Comment $comment) // Faqat Comment obyektini qabul qiladi
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database']; // Agar email ham kerak bo‘lsa: ['database', 'mail']
    }

    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->comment->post_id,
            'commenter_id' => $this->comment->user_id,
            'commenter_name' => $this->comment->user->name,
            'message' => "{$this->comment->user->name} sizning postingizga izoh qoldirdi!",
            'link' => route('posts.show', $this->comment->post_id),
        ];
    }
}
