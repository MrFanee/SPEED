<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;

class UserCreateController extends Controller
{
    public function create()
    {
        return view('user.create');
    }
}