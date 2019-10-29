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
        Eje::create(
            ['nombre' => "Eje1", "descripcion" => "Descripcion", "codigo" => "c1"], 
            ['nombre' => "Eje2", "descripcion" => "Descripcion", "codigo" => "c2"], 
            ['nombre' => "Eje3", "descripcion" => "Descripcion", "codigo" => "c3"], 
            ['nombre' => "Eje4", "descripcion" => "Descripcion", "codigo" => "c4"]
        );
    }
}
