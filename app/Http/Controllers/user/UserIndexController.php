<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;

class UserIndexController extends Controller
{
    public function index()
    {
        $query = request()->query('query'); 

        $users = User::when($query, function ($q) use ($query) {
            $q->where('username', 'like', "%$query%")
              ->orWhere('role', 'like', "%$query%");
        })->get();

        return view('user.index', compact('users', 'query'));
    }
}
