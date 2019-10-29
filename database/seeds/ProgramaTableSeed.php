<?php

use App\Models\Programa;
use Illuminate\Database\Seeder;

class ProgramaTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Programa::truncate();
        Programa::create(
            ['linea_id' => 1, 'nombre' => "Programa 1", "descripcion" => "Descripcion", "codigo" => "l1"],
            ['linea_id' => 2, 'nombre' => "Programa 2", "descripcion" => "Descripcion", "codigo" => "l2"],
            ['linea_id' => 3, 'nombre' => "Programa 3", "descripcion" => "Descripcion", "codigo" => "l3"],
            ['linea_id' => 1, 'nombre' => "Programa 4", "descripcion" => "Descripcion", "codigo" => "l4"],
            ['linea_id' => 2, 'nombre' => "Programa 5", "descripcion" => "Descripcion", "codigo" => "l5"],
            ['linea_id' => 3, 'nombre' => "Programa 6", "descripcion" => "Descripcion", "codigo" => "l6"],
            ['linea_id' => 4, 'nombre' => "Programa 7", "descripcion" => "Descripcion", "codigo" => "l7"],
            ['linea_id' => 4, 'nombre' => "Programa 8", "descripcion" => "Descripcion", "codigo" => "l8"]
        );
    }
}
