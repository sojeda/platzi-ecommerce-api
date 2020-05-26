<?php

namespace Tests\Unit;

use App\Category;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_category_has_many_products()
    {
        $category = factory(Category::class)->create();
        $product1 = factory(Product::class)->create(['category_id' => $category->id]);
        $product2 = factory(Product::class)->create(['category_id' => $category->id]);
        $product3 = factory(Product::class)->create(['category_id' => $category->id]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $category->products);
        $this->assertEquals(3, $category->products->count());
    }
}
