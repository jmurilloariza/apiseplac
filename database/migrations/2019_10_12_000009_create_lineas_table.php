<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineasTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'lineas';

    /**
     * Run the migrations.
     * @table lineas
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('eje_id')->unsigned();;
            $table->string('nombre', 45);
            $table->string('descripcion', 250);
            $table->string('codigo', 8)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(["eje_id"], 'fk_lineas_ejes1_idx');

            $table->foreign('eje_id', 'fk_lineas_ejes1_idx')
                ->references('id')->on('ejes')
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
