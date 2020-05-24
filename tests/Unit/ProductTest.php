<?php

namespace Tests\Unit;

use App\Category;
use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_product_belongs_to_category()
    {
        $category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_a_product_belongs_to_user()
    {
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $product->createdBy);
        $this->assertEquals($user->id, $product->createdBy->id);
    }
}
