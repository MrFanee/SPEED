<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;

class UserIndexController extends Controller
{
    public function index()
    {
        $users = User::with('vendor');

        $users = $users->get();
        return view('user.index', compact('users'));
    }
}
