<?php

namespace Sendportal\Base\Listeners\SMTPhooks;

use Illuminate\Support\Facades\Log;
use jdavidbakr\MailTracker\Events\LinkClickedEvent;
use Sendportal\Base\Models\Message;
use Sendportal\Base\Services\Webhooks\EmailWebhookService;

class EmailLinkClicked
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
     * @param LinkClickedEvent $event
     * @return void
     */
    public function handle(LinkClickedEvent $event)
    {
        Log::log('info', 'Email link clicked', ['event' => $event]);
        $msg = Message::query()->where('message_id', $event->sent_email->getHeader('X-Mailer-Hash'))->first();
        $ip = $event->ip_address;
        $emailWebhookService = new EmailWebhookService();
        $emailWebhookService->handleClick($msg->message_id, now(), $event->link_url);
        // Access the model using $event->sent_email
        // Access the IP address that triggered the event using $event->ip_address
    }

}
