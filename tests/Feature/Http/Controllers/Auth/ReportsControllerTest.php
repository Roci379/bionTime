<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Factories\UserFactory;
use App\Models\User;

class ReportsControllerTest extends TestCase
{
    /**
     * To test if Reports view loads correctly
     */
    public function testReportsView(){
        // Admin por defecto
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');

        $response = $this->get(route('reports'));
        $response->assertStatus(200);
        $response->assertViewIs('reports');

        $users = User::where('admin', false)->orderBy('id')->get();

        foreach($users as $us){
            $name = $us->first_name.' '.$us->last_name;
            $response->assertSee($name);
        }
        
    }
}
