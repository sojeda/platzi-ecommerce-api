<?php

namespace App\Listeners;

use App\Events\ModelRated;
use App\Notifications\ModelRatedNotification;
use App\Product;

class SendEmailModelRatedNotification
{
    public function handle(ModelRated $event)
    {
        $rateable = $event->getRateable();

        if ($rateable instanceof Product) {
            $notification = new ModelRatedNotification(
                $event->getQualifier()->name,
                $rateable->name,
                $event->getScore()
            );
            $rateable->createdBy->notify($notification);
        }
    }
}
