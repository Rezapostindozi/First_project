<?php

namespace App\Listeners;

use App\Jobs\SendWelcomeEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmailListener implements ShouldQueue
{

    public function handle(object $event): void
    {
        dispatch(new SendWelcomeEmailJob($event->user));
    }
}
