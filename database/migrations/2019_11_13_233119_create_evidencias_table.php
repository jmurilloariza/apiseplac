<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evidencias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 256);
            $table->integer('obsevacion_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->index(["obsevacion_id"], 'fk_observacion_idx_p');

            $table->foreign('obsevacion_id', 'fk_observacion_idx_p')
                ->references('id')->on('observaciones')
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
        Schema::dropIfExists('evidencias');
    }
}
