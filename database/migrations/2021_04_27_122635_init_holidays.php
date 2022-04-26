<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InitHolidays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        DB::statement("
            CREATE TABLE companies(
                id         serial PRIMARY KEY,
                name       character varying (50) UNIQUE NOT NULL,
                created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
                updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
                deleted_at TIMESTAMP(0)
            );
        ");
        DB::statement("
            INSERT INTO companies(name) VALUES ('Biome Makers Inc.'), ('Biome Makers Spain S.L.');
        ");

        DB::statement("
            CREATE TABLE holidays(
                id           serial PRIMARY KEY,
                user_id      integer NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
                staring_date TIMESTAMP(0) NOT NULL,
                ending_date  TIMESTAMP(0) NOT NULL,
                created_at   TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
                updated_at   TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
                deleted_at   TIMESTAMP(0)
            );
        ");

        DB::statement("
            CREATE TABLE holidays_limits(
                id             serial PRIMARY KEY,
                company_id     integer UNIQUE NOT NULL REFERENCES companies(id) ON UPDATE CASCADE ON DELETE CASCADE,
                number_of_days integer NOT NULL
            );
        ");

        DB::statement("
            INSERT INTO holidays_limits(company_id, number_of_days) VALUES
                ((SELECT id FROM companies WHERE name = 'Biome Makers Inc.'), 10), 
                ((SELECT id FROM companies WHERE name = 'Biome Makers Spain S.L.'), 23);
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        DB::statement("DROP TABLE holidays_limits");
        DB::statement("DROP TABLE holidays");
        DB::statement("DROP TABLE companies");
    }
}
