<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ProductTest extends TestCase
{
    public function testCreateReturnErrorOnInvalidParams()
    {
        // empty name and price
        $data = [];
        $response = $this->post(route('products.store', $data));
        $response->assertStatus(400);
    }

    public function testCreateShouldReturnHttpCreatedOnSuccess()
    {
        $data = [
            'name' => $this->faker->name,
            'price' => $this->faker->randomDigitNotNull,
        ];
        $response = $this->json('POST', '/api/products', $data);

        $response->assertStatus(201)
            ->assertJson([
                'product' => [
                    'name' => $data['name'],
                    'price' => $data['price'],
                ]
            ]);

        $json = $response->getData(true);
        $this->assertNotEmpty($json['product']['id']);

        $this->assertDatabaseHas('product', [
            'id' => $json['product']['id'],
            'name' => $json['product']['name'],
        ]);
    }
}
