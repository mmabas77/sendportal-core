<?php

namespace Sendportal\Base\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use jdavidbakr\MailTracker\Events\ComplaintMessageEvent;
use jdavidbakr\MailTracker\Events\EmailDeliveredEvent;
use jdavidbakr\MailTracker\Events\EmailSentEvent;
use jdavidbakr\MailTracker\Events\LinkClickedEvent;
use jdavidbakr\MailTracker\Events\PermanentBouncedMessageEvent;
use jdavidbakr\MailTracker\Events\ViewEmailEvent;
use Sendportal\Base\Events\MessageDispatchEvent;
use Sendportal\Base\Events\SubscriberAddedEvent;
use Sendportal\Base\Events\Webhooks\MailgunWebhookReceived;
use Sendportal\Base\Events\Webhooks\MailjetWebhookReceived;
use Sendportal\Base\Events\Webhooks\PostalWebhookReceived;
use Sendportal\Base\Events\Webhooks\PostmarkWebhookReceived;
use Sendportal\Base\Events\Webhooks\SendgridWebhookReceived;
use Sendportal\Base\Events\Webhooks\SesWebhookReceived;
use Sendportal\Base\Listeners\MessageDispatchHandler;
use Sendportal\Base\Listeners\SMTPhooks\BouncedEmail;
use Sendportal\Base\Listeners\SMTPhooks\EmailComplaint;
use Sendportal\Base\Listeners\SMTPhooks\EmailDelivered;
use Sendportal\Base\Listeners\SMTPhooks\EmailLinkClicked;
use Sendportal\Base\Listeners\SMTPhooks\EmailSent;
use Sendportal\Base\Listeners\SMTPhooks\EmailViewed;
use Sendportal\Base\Listeners\Webhooks\HandleMailgunWebhook;
use Sendportal\Base\Listeners\Webhooks\HandleMailjetWebhook;
use Sendportal\Base\Listeners\Webhooks\HandlePostalWebhook;
use Sendportal\Base\Listeners\Webhooks\HandlePostmarkWebhook;
use Sendportal\Base\Listeners\Webhooks\HandleSendgridWebhook;
use Sendportal\Base\Listeners\Webhooks\HandleSesWebhook;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MailgunWebhookReceived::class => [
            HandleMailgunWebhook::class,
        ],
        MessageDispatchEvent::class => [
            MessageDispatchHandler::class,
        ],
        PostmarkWebhookReceived::class => [
            HandlePostmarkWebhook::class,
        ],
        SendgridWebhookReceived::class => [
            HandleSendgridWebhook::class,
        ],
        SesWebhookReceived::class => [
            HandleSesWebhook::class
        ],
        MailjetWebhookReceived::class => [
            HandleMailjetWebhook::class
        ],
        PostalWebhookReceived::class => [
            HandlePostalWebhook::class
        ],
        SubscriberAddedEvent::class => [
            // ...
        ],

        // MailTracker events ...
        EmailSentEvent::class => [
            EmailSent::class,
        ],
        ViewEmailEvent::class => [
            EmailViewed::class,
        ],
        LinkClickedEvent::class => [
            EmailLinkClicked::class,
        ],
        EmailDeliveredEvent::class => [
            EmailDelivered::class,
        ],
        ComplaintMessageEvent::class => [
            EmailComplaint::class,
        ],
        PermanentBouncedMessageEvent::class => [
            BouncedEmail::class,
        ],
        // ---------------------
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
