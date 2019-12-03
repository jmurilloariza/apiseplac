<?php

use App\User;
use Illuminate\Database\Seeder;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('adminadmin'),
            'name' => 'Administrador',
            'apellidos' => 'Seplac',
            'codigo' => null,
            'rol_id' => 1,
            'programa_academico_id' => null, 
            'contrato' => null
        ]);
    }
}
