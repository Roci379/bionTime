<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FunctionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Hours that a user must work
      DB::statement("
        ALTER TABLE users ADD COLUMN weekly_hours integer;
      ");

        // Profile image of user
      DB::statement("
        ALTER TABLE users ADD COLUMN profile_image varchar(500);
      ");

      // Event visibility
      DB::statement("
        ALTER TABLE users ADD COLUMN event_visibility boolean;
      ");

        // Create table function
      DB::statement("
        CREATE TABLE function(
          id        serial PRIMARY KEY,
          name      varchar(255)
        );
      ");

        // Create table functionOfUser
      DB::statement("
        CREATE TABLE functionOfUser(
          id  serial PRIMARY KEY,
          user_id      integer NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          function_id  integer NOT NULL REFERENCES function(id) ON UPDATE CASCADE ON DELETE RESTRICT     
        );
      ");   

        // Delete user records
        DB::statement("
          DELETE FROM RECORDS;
        ");
  
          // Delete users
        DB::statement("
          DELETE FROM USERS; 
        ");
  
        // Create functions
        DB::statement("
          INSERT INTO function(id,name) VALUES (1,'front developer'), (2,'back developer'), (3, 'ceo'), (4, 'human resources');
        ");
  

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::statement("DROP TABLE function");
      DB::statement("DROP TABLE functionOfUser");
    }
}
