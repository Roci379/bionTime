<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Fewrecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("
            INSERT INTO users(email, email_verified_at, password, first_name, last_name, phone, company_id)
                 VALUES
                 ('demo@biomemakers.com','2021-05-13','', 'Demo', 'User', '66899', 2);
        ");

        DB::statement("
            INSERT INTO records (user_id, device_id, date, record_type)
                 VALUES 
                 (1,1, '2021-04-09 08:05:00', 'start'),
                 (1,1, '2021-04-09 11:20:00', 'pause'),
                 (1,1, '2021-04-09 11:30:00', 'unpause'),
                 (1,1, '2021-04-09 18:20:00', 'stop'),
                 (1,1, '2021-04-10 08:02:00', 'start'),
                 (1,1, '2021-04-10 12:00:00', 'pause'),
                 (1,1, '2021-04-10 12:30:00', 'unpause'),
                 (1,1, '2021-04-10 18:00:00', 'stop'),
                 (1,1, '2021-04-11 09:00:00', 'start'),
                 (1,1, '2021-04-11 12:00:00', 'pause'),
                 (1,1, '2021-04-11 12:35:00', 'unpause'),
                 (1,1, '2021-04-11 18:00:00', 'stop'),
                 (1,1, '2021-04-12 08:00:00', 'start'),
                 (1,1, '2021-04-12 11:30:00', 'pause'),
                 (1,1, '2021-04-12 11:39:00', 'unpause'),
                 (1,1, '2021-04-12 18:35:00', 'stop'),
                 (1,1, '2021-04-13 08:00:00', 'start'),
                 (1,1, '2021-04-13 11:00:00', 'pause'),
                 (1,1, '2021-04-13 11:30:00', 'unpause'),
                 (1,1, '2021-04-13 18:00:00', 'stop');        
        ");

        DB::statement("
            INSERT INTO records (user_id, device_id, date, record_type)
                 VALUES 
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-09 08:05:00', 'start'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-09 11:20:00', 'pause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-09 11:30:00', 'unpause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-09 18:20:00', 'stop'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-10 08:02:00', 'start'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-10 12:00:00', 'pause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-10 12:30:00', 'unpause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-10 18:00:00', 'stop'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-11 09:00:00', 'start'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-11 12:00:00', 'pause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-11 12:35:00', 'unpause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-11 18:00:00', 'stop'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-12 08:00:00', 'start'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-12 11:30:00', 'pause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-12 11:39:00', 'unpause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-12 18:35:00', 'stop'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-13 08:00:00', 'start'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-13 11:00:00', 'pause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-13 11:30:00', 'unpause'),
                 ((SELECT id FROM users WHERE email='demo@biomemakers.com'),1, '2021-05-13 18:00:00', 'stop');
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
        DB:statement("
            DELETE FROM records WHERE user_id = 1 AND device_id = 1 AND date > '2021-05-09' AND date < '2021-05-14';
        ");
    }
}
