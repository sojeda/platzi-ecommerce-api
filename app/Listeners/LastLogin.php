<?php

namespace App\Listeners;

use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;

class LastLogin implements ShouldQueue
{
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;
        $user->last_login = Carbon::now();
        $user->save();
    }
}
