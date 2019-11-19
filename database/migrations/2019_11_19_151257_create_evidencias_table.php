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
            $table->integer('comentario_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->index(["comentario_id"], 'fk_comentarios_idx_p');

            $table->foreign('comentario_id', 'fk_comentarios_idx_p')
                ->references('id')->on('comentarios')
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
