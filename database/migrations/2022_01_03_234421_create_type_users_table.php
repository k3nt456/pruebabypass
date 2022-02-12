<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTypeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_user', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->tinyInteger('status')->default(1);
        });

                DB::table("type_user")
                ->insert([
                "type" => "administrador",
                ]);
                DB::table("type_user")
                ->insert([
                "type" => "user",
                ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_user');
    }
}
