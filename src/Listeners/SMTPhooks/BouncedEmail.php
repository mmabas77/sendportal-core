<?php

namespace Sendportal\Base\Listeners\SMTPhooks;

use jdavidbakr\MailTracker\Events\PermanentBouncedMessageEvent;
use Sendportal\Base\Models\Message;
use Sendportal\Base\Services\Webhooks\EmailWebhookService;

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
        Log::log('info', 'Email bounced permanently', ['event' => $event]);
        $msg = Message::query()->where('message_id', $event->sent_email->getHeader('X-Mailer-Hash'))->first();
        $emailWebhookService = new EmailWebhookService();
        $emailWebhookService->handlePermanentBounce($msg->message_id, now());
        // Access the model using $event->sent_email
        // Access the IP address that triggered the event using $event->ip_address
    }

}
