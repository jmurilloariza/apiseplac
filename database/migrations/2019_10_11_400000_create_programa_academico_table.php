<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class CreateProgramaAcademicoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'programa_academico';

    /**
     * Run the migrations.
     * @table dependencia
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombre', 100);
            $table->string('codigo', 8)->nullable();
            $table->integer('departamento_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->index(["departamento_id"], 'fk_departamento_idx');

            $table->foreign('departamento_id', 'fk_departamento_idx')
                ->references('id')->on('departamento')
                ->onDelete('cascade')
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
