<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Factories\UserFactory;
use App\Models\User;
use App\Models\Holiday;


class DashboardControllerTest extends TestCase
{
    /**
     * To test if Dashboard view loads correctly
     */
    public function testDashboardView(){
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    /**
     * To test if Dashboard view loads correctly
     */
    public function testDashboardEvents(){
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');
        
        $event = new Holiday;
        $event->user_id = $user->id; 
        $event->staring_date = '2022-03-11 00:00:00 ';
        $event->ending_date = '2022-03-15 00:00:00 ';
        $event->event_name = 'evento de test';
        $event->event_type = 'medical';
        $event->state = 'pending';
        $event->save();

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertSee($event->event_name);
        $response->assertSee(explode(" ",$event->staring_date)[0]);
        $response->assertSee(explode(" ",$event->ending_date)[0]);

    }

}
