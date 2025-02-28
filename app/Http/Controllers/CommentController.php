<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post = Post::findOrFail($postId);
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Agar izoh muallifi post egasi bo'lmasa, unga bildirishnoma yuborish
        if ($post->user_id !== Auth::id()) {
            $post->user->notify(new NewCommentNotification($comment)); 
        }

        return redirect()->back()->with('success', 'Izoh muvaffaqiyatli qoldirildi!');
    }
    public function destroy(Comment $comment) {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}

