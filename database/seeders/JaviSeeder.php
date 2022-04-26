<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Helpers\JH;
use Illuminate\Support\Facades\Hash;

class JaviSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $email = 'javier@biomemakers.com';
        $user = User::where('email', $email)->first();
        if(!is_null($user)){
            $user->delete();
        }


        //$user = User::factory()->count(1)->create()->first();

        $user = new User;


        $user->first_name = 'Javi';
        $user->last_name = 'Fresno';
        $user->phone = '+34 12 23 45';
        $user->email = $email;
        $user->password = Hash::make('1234');
        JH::log($user);
        $user->save();

    }
}
