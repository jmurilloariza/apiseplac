<?php

use App\Models\Departamento;
use Illuminate\Database\Seeder;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class DepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Departamento::truncate();
        Departamento::create(['id' => 1, 'nombre' => 'Departamento 1', 'codigo' => '9', 'facultad_id' => 3]);
        Departamento::create(['id' => 2, 'nombre' => 'Departamento 2', 'codigo' => '30', 'facultad_id' => 4]);
        Departamento::create(['id' => 3, 'nombre' => 'Departamento 3', 'codigo' => '47', 'facultad_id' => 5]);
        Departamento::create(['id' => 4, 'nombre' => 'Departamento 4', 'codigo' => '565', 'facultad_id' => 3]);
        Departamento::create(['id' => 5, 'nombre' => 'Departamento 5', 'codigo' => '64', 'facultad_id' => 4]);
    }
}
