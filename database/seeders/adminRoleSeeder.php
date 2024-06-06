<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class adminRoleSeeder extends Seeder
{
    public function run(): void
    {
        //сидер для роли админа
            Role::insert([
                'id' => '1',
                'name_roles' => 'admin',
                'deception' => 'deception',
                'yandex_cloud_id' => 'YCAJEOAZ9VhmLMCAP1lkDE5sS',
                'yandex_cloud_secret_id' => 'YCPpxF271DgcrLXl3uGgFrw9DAUQsgBMHUKjvXrk'
            ]);
            
    }
}
