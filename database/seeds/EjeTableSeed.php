<?php

use App\Models\Eje;
use Illuminate\Database\Seeder;

class EjeTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eje::create(['nombre' => "Eje1", "descripcion" => "Descripcion", "codigo" => "c1"]);
        Eje::create(['nombre' => "Eje2", "descripcion" => "Descripcion", "codigo" => "c2"]);
        Eje::create(['nombre' => "Eje3", "descripcion" => "Descripcion", "codigo" => "c3"]);
        Eje::create(['nombre' => "Eje4", "descripcion" => "Descripcion", "codigo" => "c4"]);
    }
}
