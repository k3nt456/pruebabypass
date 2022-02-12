<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpInsertForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DROP PROCEDURE IF EXISTS sp_insert_form;
        CREATE PROCEDURE sp_insert_form(
            in xidParameter int,
            in xidChannel int
            )
        BEGIN
            DECLARE codForm int;
            DECLARE xcode varchar(10);
            SET codForm=(SELECT COUNT(*)+1 FROM forms WHERE idParameter=xidParameter);
            SET xcode=(SELECT CONCAT(abrev,LPAD(codForm,6,0)) FROM parameters WHERE id=xidParameter);
            insert into forms(
                code,
                idParameter,
                idChannel,
                created_at,
                updated_at
            )
            values(
                xcode,
                xidParameter,
                xidChannel,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
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
        $procedure = "DROP PROCEDURE IF EXISTS sp_insert_form";
        DB::unprepared($procedure);
    }
}
