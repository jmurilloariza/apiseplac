<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('planaccion_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->index(["planaccion_id"], 'fk_proyectos_plan_eje_linea_programa1_idx');


            $table->foreign('planaccion_id', 'fk_proyectos_plan_eje_linea_programa1_idx')
                ->references('id')->on('plan_eje_linea_programa')
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
