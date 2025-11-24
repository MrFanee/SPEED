<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;

class UserIndexController extends Controller
{
    public function index()
    {
        $query = request()->query('query'); 

        $users = User::all();

        return view('user.index', compact('users', 'query'));
    }
}
