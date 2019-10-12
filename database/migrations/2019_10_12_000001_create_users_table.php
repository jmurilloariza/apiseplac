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
            $table->integer('dependencia_id')->unsigned();
            $table->string('name', 80);
            $table->string('apellidos', 80);
            $table->string('codigo', 8);
            $table->string('email', 50);
            $table->string('password', 120);
            
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(["rol_id"], 'fk_usuarios_roles1_idx');

            $table->index(["dependencia_id"], 'fk_usuarios_dependencia1_idx');

            $table->unique(["codigo"], 'unique_codigo');

            $table->unique(["email"], 'correo_UNIQUE');

            $table->foreign('rol_id', 'fk_usuarios_roles1_idx')
                ->references('id')->on('roles')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('dependencia_id', 'fk_usuarios_dependencia1_idx')
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
        Schema::dropIfExists('users');
    }
}
