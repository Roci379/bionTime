<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Factories\UserFactory;
use App\Models\User;

use App\Models\Holiday;


class CompanyCalendarControllerTest extends TestCase
{
    /**
     * To test if Company Calendar view loads correctly
     */
    public function testCompanyCalendarView(){
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');

        $response = $this->get(route('companycalendar'));
        $response->assertStatus(200);
        $response->assertViewIs('companycalendar');

        $all_users = User::where('event_visibility',true)->orderBy('id')->get();

        foreach($all_users as $us){
            $name = $us->first_name.' '.$us->last_name;
            $events = Holiday::where('user_id', $us->id)->get();
            if($events->count()!=0){
                $response->assertSee($name);
            }
        }
    }
}
