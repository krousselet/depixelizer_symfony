<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailService
{
    public function __construct(
        private readonly MailerInterface $mailer
    )
    {}

    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context,
    ): void
    {
        // EMAIL CREATION
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);
        //EMAIL SENDING
        $this->mailer->send($email);
    }
}