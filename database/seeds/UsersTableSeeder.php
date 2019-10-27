<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::truncate();
        User::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('adminadmin'),
            'name' => 'Administrator',
            'apellidos' => 'Murillo Ariza',
            'codigo' => '1151222',
            'rol_id' => 1,
            'programa_academico_id' => 1
        ]);
    }
}
