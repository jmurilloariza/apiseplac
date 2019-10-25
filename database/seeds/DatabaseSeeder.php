<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(FacultadesTableSeeder::class);
        $this->call(DepartamentoTableSeeder::class);
        $this->call(ProgramaAcademicoTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
