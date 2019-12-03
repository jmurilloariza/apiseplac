<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class CreateActividadesRecursosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'actividades_recursos';

    /**
     * Run the migrations.
     * @table actividades_recursos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('actividad_id')->unsigned();
            $table->integer('recursos_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->index(["recursos_id"], 'fk_actividades_has_recursos_recursos1_idx');

            $table->index(["actividad_id"], 'fk_actividades_has_recursos_actividades1_idx');


            $table->foreign('actividad_id', 'fk_actividades_has_recursos_actividades1_idx')
                ->references('id')->on('actividades')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('recursos_id', 'fk_actividades_has_recursos_recursos1_idx')
                ->references('id')->on('recursos')
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
