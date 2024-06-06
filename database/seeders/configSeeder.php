<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class configSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::insert([
            'id' => '1',
            'login' => 'admin',
            'password' => hash('sha512', 'a94599459'),
            'id_role' => '1',
        ]);
    }
}
