<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductRatingControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $normalUser;

    private User $adminUser;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->normalUser = factory(User::class)->create();
        $this->adminUser = factory(User::class)->create();
        $this->product = factory(Product::class)->create();
    }

    public function test_rate_product()
    {
        Sanctum::actingAs($this->normalUser);

        $response = $this->postJson("/api/products/{$this->product->getKey()}/rate", [
            'score' => 5,
            'comments' => 'Comment'
        ]);

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $this->assertDatabaseHas('ratings', [
            'score' => 5,
            'rateable_id' => $this->product->getKey(),
            'rateable_type' => Product::class,
            'comments' => 'Comment'
        ]);
    }

    public function test_invalid_rate_without_user_logged()
    {
        $response = $this->postJson("/api/products/{$this->product->getKey()}/rate", [
            'score' => 10
        ]);

        $response->assertUnauthorized();
    }

    public function test_invalid_rate_without_score()
    {
        Sanctum::actingAs($this->normalUser);
        $response = $this->postJson("/api/products/{$this->product->getKey()}/rate", []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'score' => 'required'
        ]);
    }

    public function test_unrate_product()
    {
        Sanctum::actingAs($this->normalUser);

        $this->normalUser->rate($this->product, 3);

        $response = $this->postJson("/api/products/{$this->product->getKey()}/unrate");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $this->assertDatabaseMissing('ratings', [
            'score' => 3,
            'rateable_id' => $this->product->getKey(),
            'rateable_type' => Product::class
        ]);
    }

    public function test_invalid_unrate_without_user_logged()
    {
        $response = $this->postJson("/api/products/{$this->product->getKey()}/unrate");

        $response->assertUnauthorized();
    }

}
