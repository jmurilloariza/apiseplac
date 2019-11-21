<?php

use App\Models\Recurso;
use Illuminate\Database\Seeder;

class RecursosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Recurso::create(['id' => 3, 'nombre' => "Recurso 3"]);
        Recurso::create(['id' => 4, 'nombre' => "Recurso 4"]);
        Recurso::create(['id' => 5, 'nombre' => "Recurso 5"]);
        Recurso::create(['id' => 6, 'nombre' => "Recurso 6"]);
        Recurso::create(['id' => 7, 'nombre' => "Recurso 7"]);
        Recurso::create(['id' => 8, 'nombre' => "Recurso 8"]);
    }
}
