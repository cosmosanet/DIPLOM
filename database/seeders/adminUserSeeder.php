<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class adminUserSeeder extends Seeder
{
    public function run(): void
    {
        //сидер для админа 
        User::insert([
            'id' => '1',
            'name' => 'Патрушев Григорий Алеквсеевич',
            'email' => 'kaba4okus@yandex.ru',
            'id_role' => '1',
        ]);
    }
}

