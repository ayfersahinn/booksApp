<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $favoriteBooks = $user->books()->wherePivot('is_favorite', true)->get();
        return view('/profile', compact('favoriteBooks'));
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
}
