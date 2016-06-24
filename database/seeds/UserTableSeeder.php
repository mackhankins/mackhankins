<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \MH\User::create([
            'email' => env('ADMIN_EMAIL'),
            'password' => bcrypt(env('ADMIN_PASSWORD')),
        ]);
    }
}
