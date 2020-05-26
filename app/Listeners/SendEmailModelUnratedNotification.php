<?php

namespace App\Listeners;

use App\Events\ModelUnrated;
use App\Notifications\ModelUnratedNotification;
use App\Product;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailModelUnratedNotification implements ShouldQueue
{
    public function handle(ModelUnrated $event)
    {
        $rateable = $event->getRateable();

        if ($rateable instanceof Product) {
            $notification = new ModelUnratedNotification(
                $event->getQualifier()->name,
                $rateable->name
            );
            $rateable->createdBy->notify($notification);
        }
    }
}
