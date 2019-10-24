<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectoProgramaTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'proyecto_programa';

    /**
     * Run the migrations.
     * @table plan_eje_linea_programa
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('programa_id')->unsigned();
            $table->integer('proyecto_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->index(["programa_id"], 'fk_programa_id');

            $table->index(["proyecto_id"], 'fk_proyecto_id');

            $table->foreign('programa_id', 'fk_programa_id')
                ->references('id')->on('programas')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreign('proyecto_id', 'fk_proyecto_id')
                ->references('id')->on('proyectos')
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
