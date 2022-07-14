<?php

namespace Tests\Feature\Http\Controllers\Api\AuthController;

/**
 * Трейт для помощи тестирования контроллера ApiAuth
 */
trait ApiJwtTestTrait
{
    /**
     * Получить JWT-токен для учетных данных.
     * Выполняет авторизацию и получает токен.
     *
     * @param  string  $email
     * @param  string  $password
     * @return string
     */
    protected function getJwtToken(string $email, string $password): string
    {
        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        return $this
            ->postJson(route('api.v1.login'), $credentials)
            ->json('access_token');
    }
}
