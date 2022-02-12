<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpInsertParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS sp_insert_parameters;
        CREATE PROCEDURE sp_insert_parameters(
            in xname varchar(100),
            in xabrev varchar(4),
            in xidinputs text(255),
            in xidchannel text(255),
            in xurl text(255),
            in xidPage int
        )
            BEGIN
                insert into parameters(
                    name,
                    abrev,
                    idinputs,
                    idchannel,
                    url,
                    idPage
                    )
                values(
                    xname,
                    xabrev,
                    xidinputs,
                    xidchannel,
                    xurl,
                    xidPage
                );
        END
        ";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $procedure = "DROP PROCEDURE IF EXISTS sp_insert_parameters";
        DB::unprepared($procedure);
    }
}
