<?php

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create(
            [ 'id' => 1, 'nombre' => 'Administrador'],
            [ 'id' => 2, 'nombre' => 'Docente'],
            [ 'id' => 3, 'nombre' => 'Administrativo']
        );
    }
}
