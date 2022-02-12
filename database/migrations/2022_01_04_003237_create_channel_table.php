<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->tinyInteger('status')->default(1);
        });

        DB::table("channel")
                ->insert(["name" => "facebook",]);
        DB::table("channel")
                ->insert(["name" => "instagram",]);
        DB::table("channel")
                ->insert(["name" => "twitter",]);
        DB::table("channel")
                ->insert(["name" => "landing page",]);
        DB::table("channel")
                ->insert(["name" => "pagina web",]);
        DB::table("channel")
                ->insert(["name" => "whatsapp",]);
        DB::table("channel")
                ->insert(["name" => "telegram",]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel');
    }
}
