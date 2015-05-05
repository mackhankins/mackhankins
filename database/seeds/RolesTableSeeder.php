<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use MH\User;
use MH\Role;

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        $adminRole = new Role();
        $adminRole->name = 'admin';
        $adminRole->save();

        $standRole = new Role();
        $standRole->name = 'verified';
        $standRole->save();

        $modRole = new Role();
        $modRole->name = 'moderator';
        $modRole->save();

        $user = User::where('email', '=', $_ENV['ADMIN_EMAIL'])->first();
        $user->attachRole($adminRole);
    }

}