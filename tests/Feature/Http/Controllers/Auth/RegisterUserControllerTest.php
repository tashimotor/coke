<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\WithFaker;

class RegisterUserControllerTest extends \Tests\TestCase
{
    use WithFaker;

    public function testRegistrationScreenCanBeRendered(): void
    {
        $this->get('/register')
            ->assertCookie('register_page_first_open_date')
            ->assertStatus(200);
    }

    public function testNewUsersCanRegister(): void
    {
        $cookieValue = date('Y-m-d H:i:s');

        $this->withCookie('register_page_first_open_date', $cookieValue)
            ->post('/register', [
                'name' => $this->faker->name(),
                'email' => $this->faker->email(),
                'password' => 'password',
                'password_confirmation' => 'password',
            ])->assertRedirect();

        $this->assertAuthenticated();

        $this->get(route('dashboard.view'))
            ->assertSee('Cookie:')
            ->assertSee($cookieValue);
    }
}
