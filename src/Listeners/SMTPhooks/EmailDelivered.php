<?php

namespace Sendportal\Base\Listeners\SMTPhooks;

use jdavidbakr\MailTracker\Events\EmailDeliveredEvent;

class EmailDelivered
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
     * @param  EmailDeliveredEvent  $event
     * @return void
     */
    public function handle(EmailDeliveredEvent $event)
    {
        // Access the model using $event->sent_email
        // Access the IP address that triggered the event using $event->ip_address
    }

}
