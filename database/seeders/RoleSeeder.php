<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'role_name' => 'actor'
        ]);
    
        Role::create([
            'role_name' => 'director'
        ]);
    
        Role::create([
            'role_name' => 'producer'
        ]);
    
        Role::create([
            'role_name' => 'composer'
        ]);
    
        Role::create([
            'role_name' => 'writer'
        ]);
    }
}