<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $rules = [
            'username' => 'required',
            'role' => 'required',
            'vendor_id' => 'required_if:role,vendor'
        ];

        $messages = [
            'username.required' => 'Username wajib diisi!',
            'role.required' => 'Role wajib dipilih!',
            'vendor_id.required_if' => 'Vendor wajib dipilih untuk role vendor!'
        ];

        $request->validate($rules, $messages);

        $users = User::findOrFail($id);

        $users->username = $request->username;
        $users->role = $request->role;
        $users->vendor_id = $request->vendor_id;

        if ($request->filled('password')) {
            $users->password = Hash::make($request->password);
        }

        $users->save();

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate!');
    }
}
