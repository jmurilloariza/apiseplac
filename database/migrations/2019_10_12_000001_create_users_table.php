<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('rol_id')->unsigned();
            $table->integer('programa_academico_id')->unsigned()->nullable();
            $table->string('name', 80);
            $table->string('apellidos', 80);
            $table->string('codigo', 8)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('contrato', 50)->nullable();
            $table->string('password', 120);
            
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(["rol_id"], 'fk_usuarios_roles1_idx');

            $table->index(["programa_academico_id"], 'fk_programa_academico_idx');

            $table->foreign('rol_id', 'fk_usuarios_roles1_idx')
                ->references('id')->on('roles')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('programa_academico_id', 'fk_programa_academico_idx')
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
        Schema::dropIfExists('users');
    }
}
