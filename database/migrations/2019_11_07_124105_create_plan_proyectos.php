<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanProyectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_proyectos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('programa_academico_id')->unsigned();
            $table->string('nombre', 80);
            $table->string('descripcion', 250);
            $table->string('objetivo', 200);

            $table->index(["programa_academico_id"], 'fk_proyectos_programa_academico1_idx');

            $table->foreign('programa_academico_id', 'fk_proyectos_programa_academico1_idx')
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
        Schema::dropIfExists('plan_proyectos');
    }
}
