<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Factories\UserFactory;
use App\Models\User;


class ManagementControllerTest extends TestCase
{
    /**
     * To test if Management view loads correctly
     */
    public function testManagementView(){
        // Admin por defecto
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');

        $response = $this->get(route('management'));
        $response->assertStatus(200);
        $response->assertViewIs('management');

        $users = User::orderBy('id')->get();

        foreach($users as $us){
            $response->assertSee($us->id);
            $name = $us->first_name.' '.$us->last_name;
            // No pruebo el nombre entero por los caracteres raros que genera 
            // el faker
            $response->assertSee($us->first_name);
            $response->assertSee($us->email);
            $response->assertSee($us->phone);

        }
        
    }
}
