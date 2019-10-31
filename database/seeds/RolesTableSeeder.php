<?php

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Rol::truncate();
        Rol::create(['id' => 1, 'nombre' => 'Administrador']);
        Rol::create(['id' => 2, 'nombre' => 'Docente']);
        Rol::create(['id' => 3, 'nombre' => 'Administrativo']);
    }
}
