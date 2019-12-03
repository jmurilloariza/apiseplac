<?php

use App\Models\Rol;
use Illuminate\Database\Seeder;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Rol::truncate();
        Rol::create(['id' => 1, 'nombre' => 'Administrador']);
        Rol::create(['id' => 2, 'nombre' => 'Administrativo']);
        Rol::create(['id' => 3, 'nombre' => 'Docente']);
        Rol::create(['id' => 4, 'nombre' => 'Director de programa']);
    }
}
