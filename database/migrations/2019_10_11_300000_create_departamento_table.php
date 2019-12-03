<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class CreateDepartamentoTable extends Migration
{

    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'departamento';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departamento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('facultad_id')->unsigned();;
            $table->string('nombre', 100);
            $table->string('codigo', 8)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(["facultad_id"], 'fk_facultad_idx');

            $table->foreign('facultad_id', 'fk_facultad_idx')
                ->references('id')->on('facultad')
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
        Schema::dropIfExists('departamento');
    }
}
