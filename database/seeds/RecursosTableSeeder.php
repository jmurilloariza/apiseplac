<?php

use App\Models\Recurso;
use Illuminate\Database\Seeder;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class RecursosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Recurso::create(['id' => 1, 'nombre' => "Recurso 1"]);
        Recurso::create(['id' => 2, 'nombre' => "Recurso 2"]);
        Recurso::create(['id' => 3, 'nombre' => "Recurso 3"]);
        Recurso::create(['id' => 4, 'nombre' => "Recurso 4"]);
        Recurso::create(['id' => 5, 'nombre' => "Recurso 5"]);
        Recurso::create(['id' => 6, 'nombre' => "Recurso 6"]);
    }
}
