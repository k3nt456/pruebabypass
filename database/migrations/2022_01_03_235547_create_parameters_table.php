<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('abrev', 4);
            $table->text('idinputs');
            $table->text('idchannel')->nullable();
            $table->text('url');
            $table->unsignedBigInteger('idPage');
            $table->foreign('idPage')->references('id')->on('page');
            $table->tinyInteger('status')->default(1);
        });
                    DB::table("parameters")
                    ->insert([
                    "name" => "Lo quiero",
                    "abrev" => "NUPY",
                    "idinputs" => '[{"input1":"rut"},{"input2":"telefono"},{"input3":"email"}]',
                    "url" => "http://loyverse.nupy.cl/?page=facebook&campaign=¡Lo quiero!",
                    "idPage" => "1",
                    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameters');
    }
}
