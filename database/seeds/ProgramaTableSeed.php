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
        Programa::truncate();
        Programa::create(
            ['linea_id' => 1, 'nombre' => "Programa 1"],
            ['linea_id' => 2, 'nombre' => "Programa 2"],
            ['linea_id' => 3, 'nombre' => "Programa 3"],
            ['linea_id' => 1, 'nombre' => "Programa 4"],
            ['linea_id' => 2, 'nombre' => "Programa 5"],
            ['linea_id' => 3, 'nombre' => "Programa 6"],
            ['linea_id' => 4, 'nombre' => "Programa 7"],
            ['linea_id' => 4, 'nombre' => "Programa 8"]
        );
    }
}
