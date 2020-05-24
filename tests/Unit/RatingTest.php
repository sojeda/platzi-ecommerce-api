<?php

namespace Tests\Unit;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_product_belongs_to_many_users()
    {
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->ratings);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $product->ratings);
    }
}
