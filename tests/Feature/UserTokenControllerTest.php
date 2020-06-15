<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_token()
    {
        /** @var User $user */
        $user = factory(User::class)->create([
            'password' => bcrypt('12345678')
        ]);

        $response = $this->postJson('/api/sanctum/token', [
            'email' => $user->email,
            'password' => 12345678,
            'device_name' => 'mobile'
        ]);

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonStructure(['token']);
    }
}
