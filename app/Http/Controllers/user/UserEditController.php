<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;

class UserEditController extends Controller
{
    public function edit($id)
    {
        $users = User::findOrFail($id);
        return view('user.edit', compact('users'));
    }
}