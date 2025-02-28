<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewCommentNotification;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'content'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            // Post egasiga bildirishnoma yuborish
            if ($comment->post->user_id !== $comment->user_id) {
                $postOwner = $comment->post->user;
                
                // Post egasiga `notify()` orqali bildirishnoma yuboriladi
                $postOwner->notify(new NewCommentNotification($comment));
            }
        });
    }
}
