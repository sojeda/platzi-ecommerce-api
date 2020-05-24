<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        factory(Category::class, 5)->create();

        $response = $this->getJson('/api/categories');

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(5, 'data');
    }

    public function test_create_new_category()
    {
        Sanctum::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        $data = [
            'name' => 'Hola',
        ];
        $response = $this->postJson('/api/categories', $data);

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_update_category()
    {
        Sanctum::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        /** @var Category $category */
        $category = factory(Category::class)->create();

        $data = [
            'name' => 'Update Category',
        ];

        $response = $this->patchJson("/api/categories/{$category->getKey()}", $data);

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_unique_name_create_category()
    {
        Sanctum::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        $data = [
            'name' => 'Hola',
        ];

        $this->postJson('/api/categories', $data);
        $response = $this->postJson('/api/categories', $data);

        $response->assertJsonValidationErrors([
            'name'
        ]);
    }

    public function test_show_category()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->getJson("/api/categories/{$category->getKey()}");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_delete_category()
    {
        Sanctum::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->deleteJson("/api/categories/{$category->getKey()}");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $this->assertDeleted($category);
    }

}
