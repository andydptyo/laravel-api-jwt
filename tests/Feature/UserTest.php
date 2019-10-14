<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class UserTest extends TestCase
{
    public function testRegisterReturnErrorOnDuplicateEmail()
    {
	    $data = factory(\App\User::class)->create();

	    $response = $this->post(route('api.register', ['email' => $data->email]));

	    $response->assertStatus(400)
		    ->assertJsonFragment([
			    'email' => ['The email has already been taken.']
		    ]);
    }

    public function testRegisterReturnErrorOnInvalidParams()
    {
	// empty password confirmation
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password
        ];

        $response = $this->post(route('api.register', $data));

        $response->assertStatus(400);
	// TODO: assert message jika perlu

	// empty password
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->post(route('api.register', $data));

        $response->assertStatus(400);
	// TODO: assert message jika perlu

	// empty email
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->post(route('api.register', $data));

        $response->assertStatus(400);
    }

    /** @test */
   
    public function testRegisterShouldReturnHttpCreatedOnSuccess()
    {
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => $password,
            'password_confirmation' => $password
        ];
	$timeNow = Carbon::now()->toDateTimeString();

        $response = $this->post(route('api.register', $data));

        $response->assertStatus(201)
            ->assertJson([
                'user' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'created_at' => $timeNow,
                    'updated_at' => $timeNow,
                    'id' => 1
                ]
            ]);
    }
}
