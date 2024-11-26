<?php

namespace Sendportal\Base\Listeners\SMTPhooks;

use jdavidbakr\MailTracker\Events\PermanentBouncedMessageEvent;

class BouncedEmail
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
     * @param  PermanentBouncedMessageEvent  $event
     * @return void
     */
    public function handle(PermanentBouncedMessageEvent $event)
    {
        // Access the model using $event->sent_email
        // Access the IP address that triggered the event using $event->ip_address
    }

}
