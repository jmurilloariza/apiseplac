<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('actividad_id')->unsigned();
            $table->string('periodo_evaluado', 45);
            $table->date('fecha_seguimiento');
            $table->integer('valoracion');
            $table->string('situacion_actual', 45);

            $table->timestamps();
            $table->softDeletes();

            $table->index(["actividad_id"], 'fk_actividad_seguimiento');

            $table->foreign('actividad_id', 'fk_actividad_seguimiento')
                ->references('id')->on('actividades')
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
