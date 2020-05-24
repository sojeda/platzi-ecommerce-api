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
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var Product $product */
        $product = factory(Product::class)->create();

        $user->rate($product, 5);

        // dd($user->ratings()->get());
        // dd($product->ratings()->get())

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->ratings);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $product->ratings);
    }

    public function test_averageRating()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var User $user2 */
        $user2 = factory(User::class)->create();
        /** @var Product $product */
        $product = factory(Product::class)->create();

        $user->rate($product, 5);
        $user2->rate($product, 10);

        $this->assertEquals(7.5, $product->averageRating());
    }
}
