<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InitTablesNew2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        DB::statement("
            CREATE TABLE company_offices(
                id          serial PRIMARY KEY,
                name        character varying(50) UNIQUE NOT NULL,
                address     character varying(200),
                active      boolean NOT NULL,
                province_id integer NOT NULL REFERENCES intel_province(id) ON UPDATE CASCADE ON DELETE RESTRICT,
                office_id   integer NOT NULL REFERENCES companies(id) ON UPDATE CASCADE ON DELETE RESTRICT,
                created_at  TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
                updated_at  TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC')
            );
        ");
        DB::statement("
            INSERT INTO company_offices(name, active, address, province_id, office_id) VALUES 
                ('3rd Street Lab', FALSE, '', (SELECT id FROM intel_province WHERE province_name = 'California'), (SELECT id FROM companies WHERE name = 'Biome Makers Inc.')),
                ('Bayer Lab', TRUE, '', (SELECT id FROM intel_province WHERE province_name = 'California'), (SELECT id FROM companies WHERE name = 'Biome Makers Inc.')),
                ('CTTA Offices', FALSE, '', (SELECT id FROM intel_province WHERE province_name = 'Valladolid'), (SELECT id FROM companies WHERE name = 'Biome Makers Spain S.L.')),
                ('Itacyl Lab', FALSE, '', (SELECT id FROM intel_province WHERE province_name = 'Valladolid'), (SELECT id FROM companies WHERE name = 'Biome Makers Spain S.L.')),
                ('BMK Spain Offices', TRUE, '', (SELECT id FROM intel_province WHERE province_name = 'Valladolid'), (SELECT id FROM companies WHERE name = 'Biome Makers Spain S.L.'));
        ");
        DB::statement("DROP TYPE IF EXISTS enum_device_types;");
        DB::statement("CREATE TYPE enum_device_types AS ENUM('physical', 'virtual');");
        DB::statement("
            CREATE TABLE devices(
                id         serial PRIMARY KEY,
                name       character varying(20) UNIQUE NOT NULL,
                type       enum_device_types NOT NULL,
                office_id  integer REFERENCES company_offices(id) ON UPDATE CASCADE ON DELETE RESTRICT,
                created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
                updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC')
            );
        ");
        DB::statement("
            INSERT INTO devices (name,type,office_id) VALUES 
                ('FichajeApp Web',       'virtual',  NULL),
                ('FichajeApp APP',       'virtual',  NULL),
                ('card reader m-X4389',  'physical', (SELECT id FROM company_offices WHERE name = 'Bayer Lab')),
                ('lector tarjetas-FF84', 'physical', (SELECT id FROM company_offices WHERE name = 'BMK Spain Offices'));
        ");
        DB::statement("DROP TYPE IF EXISTS enum_record_types;");
        DB::statement("CREATE TYPE enum_record_types AS ENUM('start','pause','unpause','stop');");
        DB::statement("
            CREATE TABLE records(
                id          serial PRIMARY KEY,
                user_id     integer NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
                device_id   integer NOT NULL REFERENCES devices(id) ON UPDATE CASCADE ON DELETE RESTRICT,
                date        TIMESTAMPTZ(0) NOT NULL,
                record_type enum_record_types NOT NULL,
                created_at  TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
                updated_at  TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
                deleted_at  TIMESTAMP(0),
                UNIQUE(user_id, date)
            );
        ");

        DB::statement("
            CREATE TABLE office_timelimits(
                id                 serial PRIMARY KEY,
                office_id          integer UNIQUE NOT NULL REFERENCES company_offices(id) ON UPDATE CASCADE ON DELETE CASCADE,
                min_starting_hour  character varying(5) NOT NULL,
                max_finishing_hour character varying(5) NOT NULL
            );
        ");

        DB::statement("
            INSERT INTO office_timelimits(office_id, min_starting_hour, max_finishing_hour) VALUES 
                ((SELECT id FROM company_offices WHERE name = 'Bayer Lab'), '09:30', '18:00'),   
                ((SELECT id FROM company_offices WHERE name = 'BMK Spain Offices'), '09:00', '22:00');
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        DB::statement("DROP TABLE records");
        DB::statement("DROP TABLE devices");
        DB::statement("DROP TABLE office_timelimits");
        DB::statement("DROP TABLE company_offices");
    }
}
