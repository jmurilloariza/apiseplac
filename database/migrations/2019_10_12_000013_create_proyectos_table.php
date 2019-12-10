<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class CreateProyectosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'proyectos';

    /**
     * Run the migrations.
     * @table proyectos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('programa_academico_id')->unsigned();
            $table->string('nombre', 250);
            $table->string('objetivo', 200);
            $table->string('descripcion', 500);
            $table->date('fecha_cierre');

            $table->softDeletes();
            $table->timestamps();

            $table->index(["programa_academico_id"], 'fk_programa_academico_idx111');

            $table->foreign('programa_academico_id', 'fk_programa_academico_idx111')
                ->references('id')->on('programa_academico')
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
