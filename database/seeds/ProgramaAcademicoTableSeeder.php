<?php

use App\Models\ProgramaAcademico;
use Illuminate\Database\Seeder;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class ProgramaAcademicoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ProgramaAcademico::truncate();
        ProgramaAcademico::create(['id' => 2, 'nombre' => 'Sistemas', 'codigo' => '1gdn', 'departamento_id' => 1]);
        ProgramaAcademico::create(['id' => 3, 'nombre' => 'Sistemas', 'codigo' => '124', 'departamento_id' => 2]);
        ProgramaAcademico::create(['id' => 4, 'nombre' => 'Sistemas', 'codigo' => '1ter', 'departamento_id' => 3]);
        ProgramaAcademico::create(['id' => 5, 'nombre' => 'Sistemas', 'codigo' => '1grh', 'departamento_id' => 4]);
        ProgramaAcademico::create(['id' => 6, 'nombre' => 'Sistemas', 'codigo' => '14fd', 'departamento_id' => 5]);
        ProgramaAcademico::create(['id' => 7, 'nombre' => 'Sistemas', 'codigo' => '12234', 'departamento_id' => 2]);
        ProgramaAcademico::create(['id' => 8, 'nombre' => 'Sistemas', 'codigo' => '12', 'departamento_id' => 1]);
    }
}
