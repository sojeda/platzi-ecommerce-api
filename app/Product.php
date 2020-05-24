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
}
