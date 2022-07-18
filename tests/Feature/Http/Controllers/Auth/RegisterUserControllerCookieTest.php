<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\WithFaker;

/**
 * Тестирование обработки куки при регистрации.
 */
class RegisterUserControllerCookieTest extends \Tests\TestCase
{
    use WithFaker;

    /**
     * Тест установки куки при посещении страницы регистрации.
     *
     * @return void
     */
    public function testCookieIsSetOnRegistrationPage(): void
    {
        $this->get('/register')
            ->assertCookie('register_page_first_open_date')
            ->assertStatus(200);
    }

    /**
     * Тестирует корректное сохранение куки в БД и последующий показ ее данных на фронте.
     *
     * @return void
     */
    public function testHandleCookieData(): void
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
