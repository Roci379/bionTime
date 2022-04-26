<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP TYPE IF EXISTS enum_event_types;");
        DB::statement("DROP TYPE IF EXISTS enum_event_states;");
        DB::statement("CREATE TYPE enum_event_types AS ENUM('free', 'medical', 'other');");
        DB::statement("CREATE TYPE enum_event_states AS ENUM('pending', 'approved', 'denied');");

        DB::statement("
          ALTER TABLE holidays ADD COLUMN event_name varchar(200);
        ");

        DB::statement("
          ALTER TABLE holidays ADD COLUMN event_type enum_event_types NOT NULL;
        ");

        DB::statement("
          ALTER TABLE holidays ADD COLUMN state enum_event_states NOT NULL;
        ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
