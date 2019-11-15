<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::truncate();
        User::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('adminadmin'),
            'name' => 'Administrator',
            'apellidos' => 'Murillo Ariza',
            'codigo' => '1151222',
            'rol_id' => 1,
            'programa_academico_id' => 2
        ]);

        /*User::create([
            'email' => 'administardor@admin.com',
            'password' => Hash::make('adminadmin'),
            'name' => 'Administrativo',
            'apellidos' => 'Murillo Ariza',
            'codigo' => '11222',
            'rol_id' => 2,
            'programa_academico_id' => 2
        ]);

        User::create([
            'email' => 'docente@admin.com',
            'password' => Hash::make('adminadmin'),
            'name' => 'Docente',
            'apellidos' => 'Murillo Ariza',
            'codigo' => '12',
            'rol_id' => 3,
            'programa_academico_id' => 2
        ]);

        User::create([
            'email' => 'administrativo@admin.com',
            'password' => Hash::make('adminadmin'),
            'name' => 'Adminisdd',
            'apellidos' => 'Murillo Ariza',
            'codigo' => '2',
            'rol_id' => 2,
            'programa_academico_id' => 2
        ]);*/
    }
}
