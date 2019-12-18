<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class CreatePlanActividades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_actividad', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('actividades_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->double('costo')->nullable();
            $table->integer('peso');
            $table->string('estado', 45);

            $table->softDeletes();
            $table->timestamps();

            $table->index(["actividades_id"], 'fk_actividad_id_idx_p');

            $table->foreign('actividades_id', 'fk_actividad_id_idx_p')
                ->references('id')->on('actividades')
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
