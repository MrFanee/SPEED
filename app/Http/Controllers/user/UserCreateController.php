<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Vendor;

class UserCreateController extends Controller
{
    public function create()
    {
        $vendor = Vendor::all();
        return view('user.create', compact('vendor'));
    }
}