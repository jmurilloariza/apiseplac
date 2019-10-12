<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObservacionesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'observaciones';

    /**
     * Run the migrations.
     * @table observaciones
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('observacion', 150);
            $table->integer('actividad_id')->unsigned();

            $table->softDeletes();

            $table->index(["actividad_id"], 'fk_observaciones_actividades1_idx');
            $table->nullableTimestamps();


            $table->foreign('actividad_id', 'fk_observaciones_actividades1_idx')
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
       Schema::dropIfExists($this->tableName);
     }
}
