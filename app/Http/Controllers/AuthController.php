<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Username harus diisi!',
                'password.required' => 'Password harus diisi!',
            ]
        );

        $user = User::where('username', $request->username)->first();

        // if ($user && Hash::check($request->password, $user->password)) {
        //     Auth::login($user, false);

        //     $request->session()->regenerate();

        //     session(['user_id' => $user->id_user]);
        //     session(['username' => $user->username]);

        //     return redirect('/dashboard');
        // }

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['loginError' => 'Username atau password salah!',])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
