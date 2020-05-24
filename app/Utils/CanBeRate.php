<?php

namespace App\Utils;

use App\User;

trait CanBeRate
{
    public function qualifiers(string $model = null)
    {
        $modelClass = $model ? (new $model)->getMorphClass() : $this->getMorphClass();

        return $this->morphToMany($modelClass, 'rateable', 'ratings', 'rateable_id', 'qualifier_id')
            ->withPivot('qualifier_type', 'score')
            ->wherePivot('qualifier_type', $modelClass)
            ->wherePivot('rateable_type', $this->getMorphClass());
    }

    public function averageRating(string $model = null): float
    {
        return $this->qualifiers($model)->avg('score') ?: 0.0;
    }
}
