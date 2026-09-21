<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    public function changePassword(Request $req)
    {
        $user = User::findOrFail(Auth::id());
        $validatePassword = $req->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6'
        ]);

        if (Hash::check($validatePassword['current_password'], $user->password)) {
            $user->password = Hash::make($validatePassword['new_password']);
            $user->save();
            return back()->with('success', 'Şifreniz başarıyla değiştirildi.');
        } else {
            return back()->withErrors([
                'password' => 'Mevcut şifreniz hatalı.'
            ])->withInput();
        }
    }
    public function updateProfile(Request $req)
    {
        $user = User::findOrFail(Auth::id());
        $validateProfile = $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email'
        ]);

        $user->name = $validateProfile['name'];
        $user->email = $validateProfile['email'];
        $user->save();
        return back()->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('mainpage');
    }
}
