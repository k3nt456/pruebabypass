<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCompanyAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_admin', function (Blueprint $table) {
            $table->id();
            $table->integer('name');
            $table->string('rut',50);
            $table->tinyInteger('status')->default(1);
        });

        DB::table("company_admin")
                ->insert([
                "name" => "12412513",
                "rut" => "81263asd-k",
                ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_admin');
    }
}
