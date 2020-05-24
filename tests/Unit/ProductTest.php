<?php

namespace Tests\Unit;

use App\Category;
use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product_with_image_and_user()
    {
        Sanctum::actingAs(
            factory(User::class)->create()
        );

        $category = factory(Category::class)->create();
        $product = Product::create([
            'name' => 'Test',
            'price' => 1000,
            'category_id' => $category->id
        ]);

        $this->assertNotNull($product->image_url);
        $this->assertNotNull($product->createdBy);
    }

    public function test_a_product_belongs_to_category()
    {
        Sanctum::actingAs(
            factory(User::class)->create()
        );

        $category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_a_product_belongs_to_user()
    {
        $user = factory(User::class)->create();

        Sanctum::actingAs($user);

        $product = factory(Product::class)->create();

        $this->assertInstanceOf(User::class, $product->createdBy);
        $this->assertEquals($user->id, $product->createdBy->id);
    }
}
