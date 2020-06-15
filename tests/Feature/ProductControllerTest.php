<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_index()
    {
        factory(Product::class, 5)->create();

        $response = $this->getJson('/api/products');

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(5, 'data');
    }

    public function test_create_new_product()
    {
        Sanctum::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        $data = [
            'name' => 'Hola',
            'price' => 1000,
        ];
        $response = $this->postJson('/api/products', $data);

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $this->assertDatabaseHas('products', $data);
    }

    public function test_validation_new_product()
    {
        Sanctum::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        $data = [
            'price' => 1000,
        ];
        $response = $this->postJson('/api/products', $data);
        $response->assertJsonValidationErrors([
            'name',
        ]);
    }

    public function test_update_product()
    {
        Sanctum::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        /** @var Product $product */
        $product = factory(Product::class)->create();

        $data = [
            'name' => 'Update Product',
            'price' => 20000,
        ];

        $response = $this->patchJson("/api/products/{$product->getKey()}", $data);
        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_show_product()
    {
        /** @var Product $product */
        $product = factory(Product::class)->create();

        $response = $this->getJson("/api/products/{$product->getKey()}");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_delete_product()
    {
        Sanctum::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        /** @var Product $product */
        $product = factory(Product::class)->create();

        $response = $this->deleteJson("/api/products/{$product->getKey()}");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $this->assertDeleted($product);
    }

}
