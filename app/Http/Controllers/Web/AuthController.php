<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validated = $req->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|min:6|confirmed'
            ],
            [
                'name.required' => 'Ad alanı zorunludur.',
                'name.max' => 'Ad en fazla 255 karakter olabilir.',

                'email.required' => 'E-posta alanı zorunludur.',
                'email.unique' => 'Bu e-posta adresi zaten kayıtlı.',

                'password.required' => 'Şifre alanı zorunludur.',
                'password.min' => 'Şifre en az 6 karakter olmalıdır.',
                'password.confirmed' => 'Şifreler eşleşmiyor.',
            ]
        );
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);
        return redirect()->route('login');
    }
    public function login(Request $req)
    {
        $credentials = $req->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => 'E-posta alanı zorunludur.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
                'password.required' => 'Şifre alanı zorunludur.',
            ]
        );
        if (Auth::attempt($credentials))
            return redirect()->route('mainpage');
        return back()->withErrors([
            'email' => 'Email veya şifre hatalı.'
        ])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('mainpage');
    }
}
