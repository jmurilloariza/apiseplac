<?php

use Illuminate\Database\Seeder;
use \App\Models\Rol;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::truncate();
        Rol::create(
            [ 'id' => 1, 'nombre' => 'Administrador'],
            [ 'id' => 2, 'nombre' => 'Docente'],
            [ 'id' => 3, 'nombre' => 'Administrativo']
        );
    }
}
