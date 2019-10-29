<?php

use App\Models\ProgramaAcademico;
use Illuminate\Database\Seeder;

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
        ProgramaAcademico::create(['id' => 1, 'nombre' => 'Sistemas', 'codigo' => '1', 'departamento_id' => 1]);
    }
}
