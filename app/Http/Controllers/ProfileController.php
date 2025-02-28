<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)
        ->withCount(['followers', 'following', 'posts']) // Follow va postlar sonini qo‘shish
        ->firstOrFail();
        
        // Agar foydalanuvchi o‘z profilini ko‘rayotgan bo‘lsa
        if (Auth::check() && Auth::user()->username === $username) {
            return view('profiles.my-profile', compact('user'));
        }
    
        // Boshqa foydalanuvchini ko‘rayotgan bo‘lsa
        return view('profiles.user-profile', compact('user'));
    }
    
    public function showProfile($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('profile.show', ['user' => $user]);
    }
    public function edit($username) {
        if ($username !== Auth::user()->username) abort(403);
        return view('profiles.edit');
    }
    public function update(Request $request, $username)
    {
        // Foydalanuvchini topish
        $user = User::where('username', $username)->firstOrFail();
    
        // Foydalanuvchi o‘z profilini tahrirlashga ruxsati borligini tekshiramiz
        if ($user->id !== Auth::id()) {
            abort(403);
        }
    
        // Ma'lumotlarni tekshirish
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Agar yangi avatar yuklangan bo‘lsa
        if ($request->hasFile('avatar')) {
            // Eski rasmini o‘chirish (agar mavjud bo‘lsa)
            if ($user->avatar) {
                \Storage::delete('public/' . $user->avatar);
            }
    
            // Yangi rasmni saqlash
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }
    
        // Yangilangan ma'lumotlarni saqlash
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);
    
        // Muvaffaqiyatli yangilanganidan keyin qaytarish
        return redirect()->route('profile.show', $user->username)->with('success', 'Profile updated successfully.');
    }
    
}