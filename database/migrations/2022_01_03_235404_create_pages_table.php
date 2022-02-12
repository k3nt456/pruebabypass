<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url',500)->nullable();
            $table->unsignedBigInteger('idAdmin');
            $table->foreign('idAdmin')->references('id')->on('company_admin');
            $table->tinyInteger('state')->default(1);
        });
                DB::table("page")
                ->insert([
                "name" => "nupy",
                "url" => "http://loyverse.nupy.cl/",
                "idAdmin" => "1",
                ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page');
    }
}
