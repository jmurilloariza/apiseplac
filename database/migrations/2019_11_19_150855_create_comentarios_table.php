<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->longText('observacion', 150);
            $table->integer('seguimiento_id')->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $table->index(["seguimiento_id"], 'fk_seguimiento_actividades1_idx');

            $table->foreign('seguimiento_id', 'fk_seguimiento_actividades1_idx')
                ->references('id')->on('seguimiento')
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
        Schema::dropIfExists('comentarios');
    }
}
