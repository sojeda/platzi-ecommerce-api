<?php

namespace App;

use App\Events\ProductCreating;
use App\Utils\CanBeRate;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use CanBeRate;

    protected $dispatchesEvents = [
        'creating' => ProductCreating::class,
    ];

    protected static function booted()
    {
        static::creating(function (Product $product) {
            $faker = \Faker\Factory::create();
            $product->image_url = $faker->imageUrl();
            $product->createdBy()->associate(auth()->user());
        });
    }

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
