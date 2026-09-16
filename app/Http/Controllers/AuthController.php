<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validated = $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);
        return redirect()->route('login');
    }
    public function login(Request $req)
    {
        $credentials = $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials))
            return redirect()->route('mainpage');
        return back()->withErrors([
            'email' => 'Email veya şifre hatalı.'
        ])->withInput();
    }
}
