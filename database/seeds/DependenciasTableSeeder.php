<?php

use Illuminate\Database\Seeder;
use \App\Models\Dependencia;

class DependenciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Dependencia::truncate();*/
        Dependencia::create(
            ['id' => 1, 'nombre' => 'Sistemas']
        );
    }
}
