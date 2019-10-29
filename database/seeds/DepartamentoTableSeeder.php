<?php

use App\Models\Departamento;
use Illuminate\Database\Seeder;

class DepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Departamento::truncate();
        Departamento::create(['id' => 1, 'nombre' => 'Sistemas', 'codigo' => '1', 'facultad_id' => 1]);
    }
}
