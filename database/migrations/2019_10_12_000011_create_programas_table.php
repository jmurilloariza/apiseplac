<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramasTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'programas';

    /**
     * Run the migrations.
     * @table programas
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('linea_id')->unsigned();
            $table->string('nombre', 45);
            $table->string('descripcion', 250);
            $table->string('codigo', 8)->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->index(["linea_id"], 'fk_programas_lineas_idx');


            $table->foreign('linea_id', 'fk_programas_lineas_idx')
                ->references('id')->on('lineas')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
