<?php

namespace Sendportal\Base\Listeners\SMTPhooks;

use Illuminate\Support\Facades\Log;
use jdavidbakr\MailTracker\Events\ComplaintMessageEvent;
use Sendportal\Base\Models\Message;
use Sendportal\Base\Services\Webhooks\EmailWebhookService;

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
        Log::log('info', 'Email complaint', ['event' => $event]);
        $msg = Message::query()->where('message_id', $event->sent_email->getHeader('X-Mailer-Hash'))->first();
        $emailWebhookService = new EmailWebhookService();
        $emailWebhookService->handleComplaint($msg->message_id, now());
        // Access the model using $event->sent_email
        // Access the IP address that triggered the event using $event->ip_address
    }

}
