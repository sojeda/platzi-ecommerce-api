<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\User;
use Faker\Generator as Faker;
use App\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->numberBetween(10000, 60000),
        'category_id' => function () {
            return Category::inRandomOrder()->first()->id;
        },
        'created_by' => function () {
            return User::inRandomOrder()->first()->id;
        },
    ];
});
