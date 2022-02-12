<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inputs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->tinyInteger('status')->default(1);
        });

        DB::table("inputs")
                ->insert(["name" => "RUT",]);
        DB::table("inputs")
                ->insert(["name" => "telefono",]);
        DB::table("inputs")
                ->insert(["name" => "correo",]);
        DB::table("inputs")
                ->insert(["name" => "nombre",]);
        DB::table("inputs")
                ->insert(["name" => "fax",]);
        DB::table("inputs")
                ->insert(["name" => "edad",]);
        DB::table("inputs")
                ->insert(["name" => "direccion",]);
        DB::table("inputs")
                ->insert(["name" => "celular",]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inputs');
    }
}
