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
        Eje::truncate();
        Eje::create(
            ['nombre' => "Eje1"], 
            ['nombre' => "Eje2"], 
            ['nombre' => "Eje3"], 
            ['nombre' => "Eje4"]
        );
    }
}
