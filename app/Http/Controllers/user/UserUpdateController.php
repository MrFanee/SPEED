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
            'role' => 'required'
        ];

        $messages = [
            'username.required' => 'Username wajib diisi!',
            'role.required' => 'Role wajib dipilih!',
        ];

        $request->validate($rules, $messages);

        $user = User::findOrFail($id);
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update($request->only(['username', 'role', 'password']));

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate!');
    }
}
