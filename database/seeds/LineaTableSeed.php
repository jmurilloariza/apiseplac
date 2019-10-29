<?php

use App\Models\Linea;
use Illuminate\Database\Seeder;

class LineaTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Linea::truncate();
        Linea::create(
            ['eje_id' => 1, 'nombre' => "Linea 1", "descripcion" => "Descripcion", "codigo" => "e2"],
            ['eje_id' => 2, 'nombre' => "Linea 2", "descripcion" => "Descripcion", "codigo" => "e1"],
            ['eje_id' => 3, 'nombre' => "Linea 3", "descripcion" => "Descripcion", "codigo" => "e3"],
            ['eje_id' => 1, 'nombre' => "Linea 4", "descripcion" => "Descripcion", "codigo" => "e4"],
            ['eje_id' => 2, 'nombre' => "Linea 5", "descripcion" => "Descripcion", "codigo" => "e5"],
            ['eje_id' => 3, 'nombre' => "Linea 6", "descripcion" => "Descripcion", "codigo" => "e6"],
            ['eje_id' => 4, 'nombre' => "Linea 7", "descripcion" => "Descripcion", "codigo" => "e7"],
            ['eje_id' => 4, 'nombre' => "Linea 8", "descripcion" => "Descripcion", "codigo" => "e8"]
        );
    }
}
