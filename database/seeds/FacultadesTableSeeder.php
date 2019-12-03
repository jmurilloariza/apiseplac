<?php

use App\Models\Facultad;
use Illuminate\Database\Seeder;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class FacultadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Facultad::truncate();
        Facultad::create(['id' => 5, 'nombre' => 'Sistemas', 'codigo' => '5']);
        Facultad::create(['id' => 3, 'nombre' => 'Derecho', 'codigo' => '2']);
        Facultad::create(['id' => 4, 'nombre' => 'Administracion', 'codigo' => '3']);

    }
}
