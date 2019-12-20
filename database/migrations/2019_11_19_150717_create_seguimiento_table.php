<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class CreateSeguimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimiento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_actividad_id')->unsigned();
            $table->string('periodo_evaluado', 45)->nullable();
            $table->date('fecha_seguimiento')->nullable();
            $table->integer('valoracion')->nullable();
            $table->string('situacion_actual', 45)->nullable();
            $table->string('estado', 45)->nullable();
            $table->integer('avance')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(["plan_actividad_id"], 'fk_actividad_seguimiento');

            $table->foreign('plan_actividad_id', 'fk_actividad_seguimiento')
                ->references('id')->on('plan_actividad')
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
        Schema::dropIfExists('seguimiento');
    }
}
