<?php

use App\Models\Facultad;
use Illuminate\Database\Seeder;

class FacultadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Facultad::truncate();
        Facultad::create(
            ['id' => 1, 'nombre' => 'Sistemas', 'codigo' => '1']
        );
    }
}
