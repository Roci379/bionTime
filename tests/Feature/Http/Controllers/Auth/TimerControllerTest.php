<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Factories\UserFactory;
use App\Models\User;
use App\Models\Record;

class TimerControllerTest extends TestCase
{
    /**
     * To test if Dashboard view loads correctly
     */
    public function testTimerView(){
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');

        $response = $this->get(route('timer'));
        $response->assertStatus(200);
        $response->assertViewIs('timer');
    }


    /**
     * To test addition of records
     */
    public function testAddingRecord(){
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');

        $response = $this->get(route('timer'));
        $response->assertStatus(200);
        $response->assertViewIs('timer');

        $response->assertSee([]);

        $record = new Record; 
        $record->record_type = 'start';
        $record->user_id = $user->id; 
        $record->device_id = 1; 
        $record->date = '2022-02-18 21:38:52+01';
        $record->save();

        $start = explode(" ", $record->date)[0];
        $start = explode("+", $start)[0];


        $record = new Record; 
        $record->record_type = 'stop';
        $record->user_id = $user->id; 
        $record->device_id = 1; 
        $record->date = '2022-02-18 21:50:52+01';
        $record->save();

        $end = explode(" ", $record->date)[1];
        $end = explode("+", $start)[0];

        $date = explode(" ", $record->date)[0];

        $response = $this->get(route('timer'));
        $response->assertStatus(200);
        $response->assertViewIs('timer');

        $response->assertSee($start);
        $response->assertSee($end);
        $response->assertSee($date);
        $response->assertSee('00:12:00');

    }

}