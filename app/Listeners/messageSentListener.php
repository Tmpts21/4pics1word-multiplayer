<?php

namespace App\Listeners;

use App\Events\messageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class messageSentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  messageSent  $event
     * @return void
     */
    public function handle(messageSent $event)
    {
        //
    }
}
