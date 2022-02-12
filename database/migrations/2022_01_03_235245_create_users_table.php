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
            $table->id();
            $table->unsignedBigInteger('idtipousuario');
            $table->foreign('idtipousuario')->references('id')->on('type_user');
            $table->unsignedBigInteger('idAdmin');
            $table->foreign('idAdmin')->references('id')->on('company_admin');
            $table->string('user_nick',20);
            $table->string('nombre',20);
            $table->text('password');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
