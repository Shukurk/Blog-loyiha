<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Show Registration Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',  
            'avatar' => 'nullable|image|max:2048',
        ]);
    
        // Store user in database
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
        ]);
    
        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }
    
        // Send email verification link to the user
        $user->sendEmailVerificationNotification();
    
        return redirect()->route('login')->with('status', 'A confirmation link has been sent to your email. Please verify your email.');
    }
    
    

    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        // Validate login request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        // Attempt to log in the user
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $user = Auth::user();
    
            // If the user is not verified, log them out and return a message
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('login')->with('status', 'Please verify your email address.');
            }
    
            // Redirect the user to their intended page or home after successful login
            return redirect()->intended('/');
        }
    
        // If login attempt fails, redirect back with error and retain input
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }
    
    // Handle Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Verify Email
    public function verifyEmail($id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Your email is already verified.');
        }

        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            throw new \Illuminate\Auth\Access\AuthorizationException;
        }

        $user->markEmailAsVerified();

        return redirect()->route('login')->with('success', 'Your email has been verified. You can now log in.');
    }
    
}
