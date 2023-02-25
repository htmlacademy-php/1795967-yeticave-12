<?php

declare(strict_types=1);

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;

/**
 * @param array $userWinner
 * @param array $email
 * @param string $textHtml
 * @return void
 * @throws TransportExceptionInterface
 */
function notifyWinner(array $userWinner, string $textHtml, array $email ): void
{
    $transport = Transport::fromDsn('smtp://' . $email['user'] . ':' . $email['password'] . '@' . $email['smtp'] . ':' . $email['port']);
    $mailer = new Mailer($transport);
    $email_send = new Email();

    $email_send->to($userWinner['email']);
    $email_send->from($email['from']);
    $email_send->subject('Hi my friend!');
    $email_send->html($textHtml);
    $mailer->send($email_send);
}
