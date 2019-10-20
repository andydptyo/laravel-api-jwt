<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use App\Product;

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

    public function testIndexShouldReturnProductList()
    {
        $product1 = new Product;
        $product1->name = $this->faker->name;
        $product1->price = $this->faker->randomDigitNotNull;
        $product1->save();

        $product2 = new Product;
        $product2->name = $this->faker->name;
        $product2->price = $this->faker->randomDigitNotNull;
        $product2->save();

        $response = $this->json('GET', '/api/products');

        $response->assertStatus(200)
            ->assertJson([
                'products' => [
                    [
                        'id' => $product1->id,
                        'name' => $product1->name,
                        'price' => $product1->price,
                    ],
                    [
                        'id' => $product2->id,
                        'name' => $product2->name,
                        'price' => $product2->price,
                    ],
                ]
            ]);
    }

    public function testGetSingleShouldReturnSingleProduct()
    {
        $product1 = new Product;
        $product1->name = $this->faker->name;
        $product1->price = $this->faker->randomDigitNotNull;
        $product1->save();

        $response = $this->json('GET', "/api/products/{$product1->id}");

        $response->assertStatus(200)
            ->assertJson([
                'product' => [
                    'id' => $product1->id,
                    'name' => $product1->name,
                    'price' => $product1->price,
                ]
            ]);
    }

    public function testGetSingleNonExistingProductShouldReturn404()
    {
        $response = $this->json('GET', "/api/products/999999");
        $response->assertStatus(404);
    }

    public function testDeleteShouldRemoveFromDatabase()
    {
        $product1 = new Product;
        $product1->name = $this->faker->name;
        $product1->price = $this->faker->randomDigitNotNull;
        $product1->save();

        $response = $this->delete("/api/products/{$product1->id}");

        $response->assertStatus(204);

        $product1 = Product::find($product1->id);
        $this->assertNull($product1);
    }

    public function testDeleteNonExistingProductShouldDoNothing()
    {
        $response = $this->delete("/api/products/999999");
        $response->assertStatus(204);
    }

    public function testUpdateProductShouldUpdateDatabase()
    {
        $product1 = new Product;
        $product1->name = $this->faker->name;
        $product1->price = $this->faker->randomDigitNotNull;
        $product1->save();

        $data = [
            'name' => $this->faker->name,
            'price' => $this->faker->randomDigitNotNull,
        ];
        $response = $this->json('PUT', "/api/products/{$product1->id}", $data);
        $response->assertStatus(200)
            ->assertJson([
                'product' => [
                    'id' => $product1->id,
                    'name' => $data['name'],
                    'price' => $data['price'],
                ]
            ]);

        $product1 = Product::find($product1->id);
        $this->assertEquals($data['name'], $product1->name);
        $this->assertEquals($data['price'], $product1->price);
    }

    public function testUpdateNonExistingProductShouldReturn404()
    {
        $response = $this->json('PUT', '/api/products/999999', []);
        $response->assertStatus(404);
    }

    public function testUpdateWithInvalidParamsShouldReturnError()
    {
        $product1 = new Product;
        $product1->name = $this->faker->name;
        $product1->price = $this->faker->randomDigitNotNull;
        $product1->save();

        // empty name and price
        $data = [];
        $response = $this->json('PUT', "/api/products/{$product1->id}", $data);
        $response->assertStatus(400);
    }
}
