<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserStoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kode_vendor' => 'required',
            'nickname' => 'required',
            'vendor_name' => 'required',
            'alamat' => 'required',
        ]);

        User::create($request->only(['username', 'password', 'role']));
        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }
}