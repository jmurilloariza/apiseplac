<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class CreateProyectosUsuariosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'proyectos_usuarios';

    /**
     * Run the migrations.
     * @table actividades_usuarios
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('proyecto_id')->unsigned();
            $table->integer('usuario_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->index(["usuario_id"], 'fk_usuario');

            $table->index(["proyecto_id"], 'fk_actividad');


            $table->foreign('proyecto_id', 'fk_actividad')
                ->references('id')->on('proyectos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('usuario_id', 'fk_usuario')
                ->references('id')->on('users')
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
