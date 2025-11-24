<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;  

class UserUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required',
            'role' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['username', 'role']));

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate!');
    }
}