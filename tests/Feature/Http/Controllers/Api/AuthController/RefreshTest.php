<?php

namespace Tests\Feature\Http\Controllers\Api\AuthController;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class RefreshTest extends TestCase
{
    use ApiJwtTestTrait;

    /**
     * Тест успешного обновления токена.
     *
     * @return void
     */
    public function testRefreshSuccessfully(): void
    {
        $password = Str::random();

        $user = User::factory()
            ->password($password)
            ->create();

        $token = $this->getJwtToken($user->email, $password);

        $response = $this->postJson(route('api.v1.refresh', ['token' => $token]));
        $response->assertStatus(Response::HTTP_OK);

        $newToken = $response->json('access_token');

        $this->assertNotEquals($token, $newToken);

        $this->postJson(route('api.v1.me', ['token' => $token]))
            ->assertStatus(Response::HTTP_OK);
    }
}
