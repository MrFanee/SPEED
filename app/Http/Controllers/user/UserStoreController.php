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
            'role' => 'required',
            'vendor_id' => 'required_if:role,vendor'
        ];

        $messages = [
            'username.required' => 'Username wajib diisi!',
            'password.required' => 'Password wajib diisi!',
            'role.required' => 'Role wajib dipilih!',
            'vendor_id.required_if' => 'Vendor wajib dipilih untuk role vendor!'
        ];

        $request->validate($rules, $messages);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'vendor_id' => $request->vendor_id
        ]);
        
        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }
}
