<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Show welcome page
    public function welcome() {
        return view('welcome');
    }

    // Show all posts
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    // Show create post form
    public function create()
    {
        return view('posts.create');
    }

    // Store a new post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->intended('/');
    }

    // Show a specific post
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // Show edit form for a post
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        return view('posts.edit', compact('post'));
    }

    // Update a post
    public function update(Request $request, Post $post)
    {
        // Validatsiya
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Ruxsat tekshirish
        if ($post->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to edit this post.');
        }
    
        // Rasm yuklangan bo'lsa
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::delete('public/' . $post->image);
            }
            $post->image = $request->file('image')->store('images', 'public');
        }
    
        // Yangilash
        $post->title = $request->title;
        $post->description = $request->description;
    
        // SAQLASH VA ERRORNI TEKSHIRISH
        if (!$post->save()) {
            dd('Post saqlanmadi', $post->toArray());
        }
    
        return redirect()->route('posts.show', $post->id)->with('success', 'Post updated successfully.');
    }
    
    // Delete a post
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete image from storage
        if ($post->image && Storage::exists('public/' . $post->image)) {
            Storage::delete('public/' . $post->image);
        }

        // Delete post
        $post->delete();

        return redirect()->route('posts.all');
    }

    // Show all posts for the user
    public function allPosts()
    {
        $posts = Post::all();
        return view('posts.allpost', compact('posts'));
    }
    public function followedPosts() {
        $user = Auth::user();
    
        if (!$user) {
            return view('welcome'); // Agar foydalanuvchi bo‘lmasa, "welcome" sahifasi ko‘rsatiladi
        }
    
        $followedUserIds = $user->following()->pluck('following_id');
        $followedPosts = Post::whereIn('user_id', $followedUserIds)->latest()->get();
        
        return view('posts.index', compact('followedPosts'));
    }
    
}
