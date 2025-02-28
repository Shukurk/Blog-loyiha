<?php
namespace App\Http\Controllers;
use App\Notifications\NewFollowerNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow($userId)
    {
        $user = User::findOrFail($userId);
        $follower = Auth::user();

        // Agar foydalanuvchi allaqachon obuna bo‘lgan bo‘lsa, hech narsa qilmaymiz
        if ($user->followers()->where('follower_id', $follower->id)->exists()) {
            return back()->with('error', 'Siz allaqachon ushbu foydalanuvchiga obuna bo‘lgansiz.');
        }

        // Obunani saqlash
        $user->followers()->attach($follower->id);

        // Bildirishnoma yuborish
        $user->notify(new NewFollowerNotification($follower));

        return back()->with('success', 'Siz ushbu foydalanuvchiga obuna bo‘ldingiz!');
    }    public function unfollow($userId) { Auth::user()->following()->detach($userId); return back(); }
}