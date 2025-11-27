<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\User;
use App\Vendor;

class UserEditController extends Controller
{
    public function edit($id)
    {
        $users = User::findOrFail($id);
        $vendorList = Vendor::all();

        return view('user.edit', [
            'users' => $users,
            'vendorList' => $vendorList]);
    }
}