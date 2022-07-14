<?php

namespace Tests\Feature\Http\Controllers\Api\AuthController;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use ApiJwtTestTrait;

    /**
     * Тестирование успешного логаута.
     *
     * @return void
     */
    public function testLogoutSuccessfully(): void
    {
        $password = Str::random();

        $user = User::factory()
            ->password($password)
            ->create();

        $token = $this->getJwtToken($user->email, $password);

        $this->postJson(route('api.v1.logout', ['token' => $token]))
            ->assertStatus(Response::HTTP_OK);

        $this->postJson(route('api.v1.me', ['token' => $token]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
