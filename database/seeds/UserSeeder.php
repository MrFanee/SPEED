<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'vendor_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'staff',
                'password' => Hash::make('123456'),
                'role' => 'staff',
                'vendor_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // [
            //     'username' => 'atmi',
            //     'password' => Hash::make('123456'),
            //     'role' => 'vendor',
            //     'vendor_id' => 100497, 
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ]);
    }
}
