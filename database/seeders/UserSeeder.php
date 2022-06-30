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
        $user->username = 'admin';
        $user->name = 'Administrator';
        $user->email = 'admin@test.co';
        $user->password = Hash::make('admin1234');
        $user->status = 1;
        $user->role = 'admin';
        $user->save();
    }
}
