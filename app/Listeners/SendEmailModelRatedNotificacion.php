<?php

namespace App\Listeners;

use App\Events\ModelRated;

class SendEmailModelRatedNotificacion
{
    public function handle(ModelRated $event)
    {
        dd($event);
    }
}
