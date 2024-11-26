<?php

namespace Sendportal\Base\Listeners\SMTPhooks;

use jdavidbakr\MailTracker\Events\ComplaintMessageEvent;

class EmailComplaint
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
     * @param  ComplaintMessageEvent  $event
     * @return void
     */
    public function handle(ComplaintMessageEvent $event)
    {
        // Access the model using $event->sent_email
        // Access the IP address that triggered the event using $event->ip_address
    }

}
