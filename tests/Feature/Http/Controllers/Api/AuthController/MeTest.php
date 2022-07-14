<?php

namespace Tests\Feature\Http\Controllers\Api\AuthController;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class MeTest extends TestCase
{
    use ApiJwtTestTrait;

    /**
     * Тест успешного получения информации об авторизованном пользователе.
     *
     * @return void
     */
    public function testMeSuccessfully(): void
    {
        $password = Str::random();

        $user = User::factory()
            ->password($password)
            ->create();

        $token = $this->getJwtToken($user->email, $password);

        $response = $this->postJson(route('api.v1.me', ['token' => $token]));
        $response->assertStatus(Response::HTTP_OK);

        $me = $response->json();

        $this->assertEquals($user->id, $me['id']);
        $this->assertEquals($user->email, $me['email']);
    }
}
