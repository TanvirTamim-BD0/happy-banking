<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'superadmin', 
            'email' => 'admindhaka@gmail.com',
            'mobile' => '01799646660',
            'password' => Hash::make('Dinajpur@2021'),
            'role' => 'superadmin',
            'status' => 1,
        ]);
    
        $role = Role::where('name','superadmin')->first();
        $permissions = Permission::pluck('id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
