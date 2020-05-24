<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings()
    {
        return $this->belongsToMany(User::class, 'ratings')
            ->using(Rating::class)
            ->as('users')
            ->withTimestamps();
    }

    public function averageRating(): float
    {
        return $this->ratings()->avg('score') ?: 0.0;
    }
}
