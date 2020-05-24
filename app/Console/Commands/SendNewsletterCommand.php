<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class SendNewsletterCommand extends Command
{
    protected $signature = 'send:newsletter';

    protected $description = 'Envia un correo electronico a todos los usuarios que hayan verificado su cuenta';

    public function handle()
    {
        User::query();
    }
}
