<?php

namespace App\Http\Controllers;

use App\Console\Commands\SendEmailVerificationReminderCommand;
use Illuminate\Support\Facades\Artisan;

class NewsletterController extends Controller
{
    public function send(): void
    {
        Artisan::call(SendEmailVerificationReminderCommand::class);
    }
}
