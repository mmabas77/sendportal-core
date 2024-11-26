<?php

namespace Sendportal\Base\Adapters;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Arr;
use jdavidbakr\MailTracker\MailTracker;
use Sendportal\Base\Services\Messages\MessageTrackingOptions;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SmtpAdapter extends BaseMailAdapter
{
    /** @var Mailer */
    protected $client;

    /** @var EsmtpTransport */
    protected $transport;

    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, MessageTrackingOptions $trackingOptions, string $content): string
    {
        try {
            $msg = $this->resolveMessage($subject, $content, $fromEmail, $fromName, $toEmail);
            $client = $this->resolveClient();
            // Hook into the mailer
            $tracker = new MailTracker;
            $tracker->messageSending(new MessageSending($msg));
            $client->send($msg);
            return $this->resolveMessageId($msg->getHeaders()->get('X-Mailer-Hash')->getBody());
        } catch (TransportException|TransportExceptionInterface $e) {
            return $this->resolveMessageId(null);
        }
    }

    protected function resolveClient(): Mailer
    {
        if ($this->client) {
            return $this->client;
        }

        $this->client = new Mailer($this->resolveTransport());

        return $this->client;
    }

    protected function resolveTransport(): EsmtpTransport
    {
        if ($this->transport) {
            return $this->transport;
        }

        $factory = new EsmtpTransportFactory();

        $encryption = Arr::get($this->config, 'encryption');

        $scheme = !is_null($encryption) && $encryption === 'tls'
            ? ((Arr::get($this->config, 'port') == 465) ? 'smtps' : 'smtp')
            : '';

        $dsn = new Dsn(
            $scheme,
            Arr::get($this->config, 'host'),
            Arr::get($this->config, 'username'),
            Arr::get($this->config, 'password'),
            Arr::get($this->config, 'port')
        );

        $this->transport = $factory->create($dsn);
        $this->transport->getStream()->setStreamOptions([
            'ssl' => ['allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false]
        ]);
        return $this->transport;
    }

    protected function resolveMessage(string $subject, string $content, string $fromEmail, string $fromName, string $toEmail): Email
    {
        $msg = (new Email())
            ->from(new Address($fromEmail, $fromName))
            ->to($toEmail)
            ->subject($subject)
            ->html($content);

        return $msg;
    }

    protected function resolveMessageId($result): ?string
    {
        if ($result == null)
            return null;
        return $result;
    }
}
