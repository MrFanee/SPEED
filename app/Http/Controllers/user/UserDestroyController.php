<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;

class UserDestroyController extends Controller
{
    public function destroy($id)
    {
        $users = User::findOrFail($id);
        $users->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}