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
        Linea::truncate();
        Linea::create(
            ['eje_id' => 1, 'nombre' => "Linea 1"],
            ['eje_id' => 2, 'nombre' => "Linea 2"],
            ['eje_id' => 3, 'nombre' => "Linea 3"],
            ['eje_id' => 1, 'nombre' => "Linea 4"],
            ['eje_id' => 2, 'nombre' => "Linea 5"],
            ['eje_id' => 3, 'nombre' => "Linea 6"],
            ['eje_id' => 4, 'nombre' => "Linea 7"],
            ['eje_id' => 4, 'nombre' => "Linea 8"]
        );
    }
}
