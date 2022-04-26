<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Factories\UserFactory;
use App\Models\User;
use App\Models\Holiday;


class MyCalendarControllerTest extends TestCase
{
    /**
     * To test if My Calendar view loads correctly
     */
    public function testMyCalendarView(){
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');

        $response = $this->get(route('mycalendar'));
        $response->assertStatus(200);
        $response->assertViewIs('mycalendar');
    }


      /**
     * To test if Dashboard view loads correctly
     */
    public function testMyCalendarEvents(){
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

        $response = $this->get(route('mycalendar'));
        $response->assertStatus(200);
        $response->assertViewIs('mycalendar');
        $year = explode("-", $event->staring_date)[0];
        $response->assertSee($year);
        $start_day = explode("-", $event->staring_date)[2];
        $start_day = explode(" ", $start_day)[0];
        $finish_day = explode("-", $event->ending_date)[2];
        $finish_day = explode(" ", $finish_day)[0];
        $response->assertSee($year);
        $response->assertSee($start_day);
        $response->assertSee($finish_day);



    }

}
