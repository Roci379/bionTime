<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Factories\UserFactory;
use App\Models\User;
use App\Models\Functions;
use App\Models\FunctionOfUser; 

class MyProfileControllerTest extends TestCase
{
     /**
     * To test if My Profile view loads correctly
     * CB - 0053: El usuario accede a la vista de My Profile y visualiza sus datos
     */
    public function testMyProfileView(){
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');


        // For roles 
        $function = Functions::first();
        $functionofuser = new FunctionOfUser; 
        $functionofuser->function_id = $function->id; 
        $functionofuser->user_id = $user->id;
        $functionofuser->save();

        $response = $this->get(route('myprofile'));
        $response->assertStatus(200);
        $response->assertViewIs('myprofile');
        $response->assertSee($user->id);
        $response->assertSee($user->first_name);
        $response->assertSee($user->last_name);
        $response->assertSee($user->email);
        $response->assertSee($user->admin);
        $response->assertSee($function->name);



    }
}
