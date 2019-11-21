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
            $table->integer('programa_academico_id')->unsigned();
            $table->string('nombre', 200)->nullable();
            $table->string('url_documento', 256);
            $table->string('periodo_inicio', 8);
            $table->string('periodo_fin', 8);
            $table->timestamps();

            $table->softDeletes();

            $table->index(["programa_academico_id"], 'fk_programa_academico_id_idx');

            $table->foreign('programa_academico_id', 'fk_programa_academico_id_idx')
                ->references('id')->on('programa_academico')
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
