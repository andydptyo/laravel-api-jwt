<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class UserTest extends TestCase
{

    /** @test */
    public function register_user_is_working()
    {
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => $password,
            'password_confirmation' => $password
        ];

        $response = $this->post(route('api.register', $data));

        $response->assertStatus(201)
            ->assertJson([
                'user' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'id' => 1
                ]
            ]);
    }
}
