<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

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
            $table->integer('proyecto_id')->unsigned();
            $table->integer('plan_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->index(["proyecto_id"], 'fk_proyecto_id_idx_p');

            $table->foreign('proyecto_id', 'fk_proyecto_id_idx_p')
                ->references('id')->on('proyectos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->index(["plan_id"], 'fk_plan_id_idx_p');

            $table->foreign('plan_id', 'fk_plan_id_idx_p')
                ->references('id')->on('plan')
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
