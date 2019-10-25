<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultadTable extends Migration
{

    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'facultad';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facultad', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100);
            $table->string('codigo', 8);
            $table->timestamps();

            $table->unique(["codigo"], 'unique_codigo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facultad');
    }
}
