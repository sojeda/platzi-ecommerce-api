<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;

class ModelUnrated
{
    private Model $qualifier;
    private Model $rateable;

    public function __construct(Model $qualifier, Model $rateable)
    {
        $this->qualifier = $qualifier;
        $this->rateable = $rateable;
    }

    public function getQualifier(): Model
    {
        return $this->qualifier;
    }

    public function getRateable(): Model
    {
        return $this->rateable;
    }
}
