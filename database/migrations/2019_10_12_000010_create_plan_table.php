<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'plan';

    /**
     * Run the migrations.
     * @table plan
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('dependencia_id')->unsigned();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->timestamps();

            $table->softDeletes();

            $table->index(["dependencia_id"], 'fk_plan_dependencia1_idx');


            $table->foreign('dependencia_id', 'fk_plan_dependencia1_idx')
                ->references('id')->on('dependencia')
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
