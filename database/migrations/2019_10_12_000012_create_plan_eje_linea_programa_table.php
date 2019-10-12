<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanEjeLineaProgramaTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'plan_eje_linea_programa';

    /**
     * Run the migrations.
     * @table plan_eje_linea_programa
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->integer('eje_id')->unsigned();
            $table->integer('linea_id')->unsigned();
            $table->integer('programa_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->index(["programa_id"], 'fk_plan_has_ejes_programas1_idx');

            $table->index(["plan_id"], 'fk_plan_has_ejes_plan1_idx');

            $table->index(["linea_id"], 'fk_plan_has_ejes_lineas1_idx');

            $table->index(["eje_id"], 'fk_plan_has_ejes_ejes1_idx');


            $table->foreign('plan_id', 'fk_plan_has_ejes_plan1_idx')
                ->references('id')->on('plan')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('eje_id', 'fk_plan_has_ejes_ejes1_idx')
                ->references('id')->on('ejes')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('linea_id', 'fk_plan_has_ejes_lineas1_idx')
                ->references('id')->on('lineas')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('programa_id', 'fk_plan_has_ejes_programas1_idx')
                ->references('id')->on('programas')
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
