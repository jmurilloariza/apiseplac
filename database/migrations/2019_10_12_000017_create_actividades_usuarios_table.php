<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesUsuariosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'actividades_usuarios';

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
            $table->integer('actividad_id')->unsigned();
            $table->integer('usuario_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->index(["usuario_id"], 'fk_usuario');

            $table->index(["actividad_id"], 'fk_actividad');


            $table->foreign('actividad_id', 'fk_actividad')
                ->references('id')->on('actividades')
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
