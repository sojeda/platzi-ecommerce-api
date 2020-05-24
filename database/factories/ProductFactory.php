<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;
use App\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->numberBetween(10000, 60000),
        'category_id' => function (array $post) {
            return Category::inRandomOrder()->first()->id;
        },
    ];
});
