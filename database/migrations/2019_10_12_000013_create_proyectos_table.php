<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'proyectos';

    /**
     * Run the migrations.
     * @table proyectos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('nombre', 250);
            $table->string('codigo', 10);
            $table->string('objetivo', 200);
            $table->string('descripcion', 500);

            $table->softDeletes();
            $table->timestamps();

            $table->index(["plan_id"], 'fk_plan_id');
            $table->unique(["codigo"], 'unique_codigo');

            $table->foreign('plan_id', 'plan_id')
                ->references('id')->on('plan')
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
