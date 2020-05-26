<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Laraveles\Rating\Models\Rating;
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
        $this->adminUser = factory(User::class)->state('admin')->create();
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

    public function test_invalid_rate_product()
    {
        Sanctum::actingAs($this->normalUser);

        $response = $this->postJson("/api/products/{$this->product->getKey()}/rate", [
            'score' => 10
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'error' => "El valor debe estar entre 1 y 5"
        ]);
    }

    public function test_approve_rating_with_admin()
    {
        Sanctum::actingAs($this->adminUser);

        $this->normalUser->rate($this->product, 3);

        $rating = Rating::first();

        $response = $this->postJson("/api/rating/{$rating->getKey()}/approve");

        $response->assertSuccessful();

        $rating->refresh();

        $this->assertNotNull($rating->approved_at);
    }

    public function test_approve_rating_without_admin()
    {
        Sanctum::actingAs($this->normalUser);

        $this->normalUser->rate($this->product, 3);

        $rating = Rating::first();

        $response = $this->postJson("/api/rating/{$rating->getKey()}/approve");

        $response->assertStatus(403);
    }

    public function test_index_rating()
    {
        Sanctum::actingAs($this->adminUser);

        $this->normalUser->rate($this->product, 3);
        $this->adminUser->rate($this->product, 3);

        $response = $this->getJson("/api/rating");

        $response->assertOk();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(2, 'data');
    }

    public function test_index_rating_with_normal_user()
    {
        Sanctum::actingAs($this->normalUser);

        $response = $this->getJson("/api/rating");

        $response->assertStatus(403);
    }
}
