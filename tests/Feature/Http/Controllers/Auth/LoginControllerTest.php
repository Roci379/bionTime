<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Factories\UserFactory;
use App\Models\User;

class LoginControllerTest extends TestCase
{

    /** @test 
     * To test if Login view loads correctly
    */
    public function testLoginView(){
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test 
     * To test if login view reloads if not data submitted
    */
    public function testLoginNotValidData(){
        $response = $this->post(route('login'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

 
    /** @test 
     * To test if login view reloads if password is not written
    */
    public function testLoginNotValidPassword2(){
        $response = $this->post(route('login'), [
            'email' => 'admin@prueba.com',
            'password' => ''
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    /** @test 
     * To test if login view reloads if email is not written
    */
    public function testLoginNotValidEmail(){
        $response = $this->post(route('login'), [
            'email' => '',
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

    }


    /** @test 
     * To test if dashboard is loaded if email and password are correct
     * CB - 0001: Inicio de sesión con datos de usuario registrado (email, contraseña) correctos.
    */
    public function testLoginValidData(){

        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');
    }

    /** @test 
     * To test if login view reloads if password does not match user
     * CB - 0002: Inicio de sesión con usuario registrado email correcto, contraseña incorrecta
    */
    public function testLoginNotValidPassword(){
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => '1234'
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }


    /** @test 
     * To test if login view reloads if email does not match any user in database
     * CB - 0003: Inicio de sesión con datos de usuario no registrado
    */
    public function testLoginNotValidUser(){
        $response = $this->post(route('login'), [
            'email' => 'emailnoendb',
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

    }

    /** @test 
     * To test if logged admin can access reports and management
     * CB - 0004: Inicio de sesión con datos de usuario registrado (email, contraseña) adminisrtador.
    */
    public function testLoginValidDataAdmin(){

        $user = User::factory()->create(
            ['admin' => true]
        );
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');
        $response = $this->get(route('management'));
        $response->assertStatus(200);
        $response->assertViewIs('management');
        $response = $this->get(route('reports'));
        $response->assertStatus(200);
        $response->assertViewIs('reports');
        
    }

    /** @test 
     * To test if logged user no admin cannot access reports and management
     * CB - 0005: Inicio de sesión con datos de usuario registrado (email, contraseña) no administrador.
    */
    public function testLoginValidDataNoAdmin(){

        $user = User::factory()->create(
            ['admin' => false]
        );
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/dashboard');
        $response = $this->get(route('management'));
        $response->assertStatus(403);
        $response = $this->get(route('reports'));
        $response->assertStatus(403);        
    }
}
