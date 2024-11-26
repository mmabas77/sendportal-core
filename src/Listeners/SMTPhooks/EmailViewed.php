<?php

namespace Sendportal\Base\Listeners\SMTPhooks;

use Illuminate\Support\Facades\Log;
use jdavidbakr\MailTracker\Events\ViewEmailEvent;

class EmailViewed
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
     * @param  ViewEmailEvent  $event
     * @return void
     */
    public function handle(ViewEmailEvent $event)
    {
        Log::log('info', 'Email viewed', ['event' => $event]);
        // Access the model using $event->sent_email
        // Access the IP address that triggered the event using $event->ip_address
    }

}
