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
        $request->validate([
            'username' => 'required',
            'role' => 'required',
            'password' => 'nullable'
        ]);

        $user = User::findOrFail($id);
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update($request->only(['username', 'role', 'password']));

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate!');
    }
}
