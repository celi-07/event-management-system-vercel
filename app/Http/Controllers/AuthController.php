<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function getAuth()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.auth', [
            'page' => 'Authentication',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            
            return redirect()->intended('/');
        }

        return back()->with('auth_error', [
            'action' => 'signIn',
            'message' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'is_organizer' => ['nullable', 'boolean'],
        ]);

        try {
            $name = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));

            $user = User::create([
                'name' => $name,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_organizer' => $request->boolean('is_organizer'),
            ]);

            Auth::login($user);
            return redirect()->intended('/');
        } catch (\Throwable $e) {
            return back()->with('auth_error', [
                'action' => 'signUp',
                'message' => 'Registration failed. Please try again.',
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth');
    }
}
