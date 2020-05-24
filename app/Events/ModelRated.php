<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelRated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Model $model;
    private Model $rateable;
    private float $score;

    public function __construct(Model $qualifier, Model $rateable, float $score)
    {
        $this->model = $qualifier;
        $this->rateable = $rateable;
        $this->score = $score;
    }
}
