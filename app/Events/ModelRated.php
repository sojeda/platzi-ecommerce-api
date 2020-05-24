<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelRated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Model $qualifier;
    private Model $rateable;
    private float $score;

    public function __construct(Model $qualifier, Model $rateable, float $score)
    {
        $this->qualifier = $qualifier;
        $this->rateable = $rateable;
        $this->score = $score;
    }

    public function getQualifier(): Model
    {
        return $this->qualifier;
    }

    public function getRateable(): Model
    {
        return $this->rateable;
    }

    public function getScore(): float
    {
        return $this->score;
    }
}
