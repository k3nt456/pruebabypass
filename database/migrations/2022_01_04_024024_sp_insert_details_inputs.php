<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpInsertDetailsInputs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS sp_insert_details_inputs;
        CREATE PROCEDURE sp_insert_details_inputs(
    		in xidForms int,
            in xidInput int,
    		in xvalue varchar(100)
            )
        BEGIN
            insert into details_inputs(
                idForms,
                idInput,
                value
            )
            values(
                xidForms,
                xidInput,
                xvalue
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
        $procedure = "DROP PROCEDURE IF EXISTS sp_insert_details_inputs";
        DB::unprepared($procedure);
    }
}
