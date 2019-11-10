<?php

use Illuminate\Database\Seeder;
use App\Models\Indicador;

class IndicadorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Indicador::create(['id' => 1, 'nombre' => "Indicador 1"]);
        Indicador::create(['id' => 2, 'nombre' => "Indicador 2"]);
        Indicador::create(['id' => 3, 'nombre' => "Indicador 3"]);
        Indicador::create(['id' => 4, 'nombre' => "Indicador 4"]);
        Indicador::create(['id' => 5, 'nombre' => "Indicador 5"]);
        Indicador::create(['id' => 6, 'nombre' => "Indicador 6"]);
        Indicador::create(['id' => 7, 'nombre' => "Indicador 7"]);
        Indicador::create(['id' => 8, 'nombre' => "Indicador 8"]);
    }
}
