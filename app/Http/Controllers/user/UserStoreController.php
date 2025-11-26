<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserStoreController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
            'role' => 'required'
        ];

        $messages = [
            'username.required' => 'Username wajib diisi!',
            'password.required' => 'Password wajib diisi!',
            'role.required' => 'Role wajib dipilih!',
        ];

        $request->validate($rules, $messages);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }
}
