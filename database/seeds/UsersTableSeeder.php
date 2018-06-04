<?php

use Illuminate\Database\Seeder;
use jeremykenedy\LaravelRoles\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        if (App\User::count() == 0) {

            $user = App\User::create([
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
            ]);

            $role = Role::where('name', '=', 'Admin')->first();  //choose the default role upon user creation.
            $user->attachRole($role);
        }
    }
}
