<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->username = 'superadmin';
        $user->name = 'Super Admin';
        $user->email = 'superadmin@test.co';
        $user->password = Hash::make('superadmin123');
        $user->status = 1;
        $user->role = 'superadmin';
        $user->save();
    }
}
