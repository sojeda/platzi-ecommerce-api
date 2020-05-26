<?php

namespace App;

use App\Events\ProductCreating;
use Illuminate\Database\Eloquent\Model;
use Laraveles\Rating\Contracts\Rateable;
use Laraveles\Rating\Traits\CanBeRated;

class Product extends Model implements Rateable
{
    use CanBeRated;

    protected $dispatchesEvents = [
        'creating' => ProductCreating::class,
    ];

    protected static function booted()
    {
        static::deleting(function (Product $product) {
            $product->qualifications()->delete();
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

    public function name(): string
    {
        return $this->name;
    }
}
