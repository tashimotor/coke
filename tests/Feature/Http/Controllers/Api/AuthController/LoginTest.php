<?php

namespace Tests\Feature\Http\Controllers\Api\AuthController;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use WithFaker;

    /**
     * Тест успешного логина.
     *
     * @return void
     */
    public function testSuccessfulLogin(): void
    {
        $password = Str::random();
        $user = User::factory()
            ->password($password)
            ->create();

        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->postJson(route('api.v1.login'), $credentials);
        $response->assertStatus(Response::HTTP_OK);

        $jwt = $response->json();

        self::assertEquals('bearer', $jwt['token_type']);
        self::assertEquals(config('jwt.ttl') * 60, $jwt['expires_in']);
        self::assertNotNull(Arr::get($jwt, 'access_token'));
    }

    /**
     * Тест неудачного логина.
     *
     * @return void
     */
    public function testFailedLogin(): void
    {
        $password = Str::random();
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->postJson(route('api.v1.login'), $credentials)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Тест ошибок валидации
     *
     * @param  array  $query
     * @param  string  $errorField
     *
     * @dataProvider dataForRequestValidationTest
     *
     * @return void
     */
    public function testFailedValidation(array $query, string $errorField): void
    {
        $this->postJson(route('api.v1.login', $query))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrorFor($errorField);
    }

    /**
     * Дата провайдер для теста валидации реквеста.
     *
     * @return array[]
     */
    public function dataForRequestValidationTest(): array
    {
        return [
            'Не указан email' => [
                ['password' => Str::random()],
                'email',
            ],
            'Не указан пароль' => [
                ['email' => 'user@user.com'],
                'password',
            ],
            'Не корректный формат email' => [
                [
                    'password' => Str::random(),
                    'email' => 'email',
                ],
                'email',
            ],
        ];
    }
}
