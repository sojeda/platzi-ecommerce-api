<?php

use App\Rating;

return [
    'models' => [
        'rating' => Rating::class,
    ],
    'from' => 1,
    'to' => 5,
    'required_approval' => true,
];
