<?php

namespace Sendportal\Base\Listeners\SMTPhooks;

use Illuminate\Support\Facades\Log;
use jdavidbakr\MailTracker\Events\ViewEmailEvent;
use Sendportal\Base\Models\Message;
use Sendportal\Base\Services\Webhooks\EmailWebhookService;

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
     * @param ViewEmailEvent $event
     * @return void
     */
    public function handle(ViewEmailEvent $event)
    {
        Log::log('info', 'Email viewed', ['event' => $event]);
        $msg = Message::query()->where('message_id', $event->sent_email->getHeader('X-Mailer-Hash'))->first();
        $ip = $event->ip_address;
        $emailWebhookService = new EmailWebhookService();
        $emailWebhookService->handleOpen($msg->message_id, now(), $ip);
        // Access the model using $event->sent_email
        // Access the IP address that triggered the event using $event->ip_address
    }

}
