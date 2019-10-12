<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'actividades';

    /**
     * Run the migrations.
     * @table actividades
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('proyecto_id')->unsigned();
            $table->integer('indicador_id')->unsigned();
            $table->string('acciones', 250);
            $table->string('descripcion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->double('costo', 8, 2);
            $table->string('unidad_medida', 60);
            $table->integer('peso');

            $table->index(["indicador_id"], 'fk_actividades_indicadores1_idx');

            $table->index(["proyecto_id"], 'fk_actividades_proyectos1_idx');
            $table->softDeletes();
            
            $table->nullableTimestamps();


            $table->foreign('proyecto_id', 'fk_actividades_proyectos1_idx')
                ->references('id')->on('proyectos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('indicador_id', 'fk_actividades_indicadores1_idx')
                ->references('id')->on('indicadores')
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
