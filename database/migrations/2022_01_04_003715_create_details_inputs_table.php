<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_inputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idForms');
            $table->foreign('idForms')->references('id')->on('forms');
            $table->unsignedBigInteger('idInput');
            $table->foreign('idInput')->references('id')->on('inputs');
            $table->string('value');
            $table->tinyInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details_inputs');
    }
}
