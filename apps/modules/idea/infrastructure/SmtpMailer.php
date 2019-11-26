<?php

namespace Idy\Idea\Infrastructure;

use Idy\Idea\Domain\Model\Email;
use Idy\Idea\Domain\Model\Mailer;
use Phalcon\Ext\Mailer\Manager;

class SmtpMailer implements Mailer
{
    private $mailer;

    public function __construct($di)
    {   
        $config = $di->get('config');
        $mailConfig = $config->mail;
        $this->mailer = new Manager([
            'driver' 	 => $mailConfig->driver,
            'host'	 	 => $mailConfig->smtp->server,
            'port'	 	 => $mailConfig->smtp->port,
            'encryption' => $mailConfig->smtp->encryption,
            'username'   => $mailConfig->smtp->username,
            'password'	 => $mailConfig->smtp->password,
            'from'		 => [
                    'email' => $mailConfig->fromEmail,
                    'name'	=> $mailConfig->fromName,
                ]
        ]);
    }

    public function send(Email $email) : void
    {   
        $viewPath = 'mail/idea_rated_plain.volt';
        $message = $this->mailer->createMessageFromView($viewPath, $email->params())
            ->to($email->recipient())
            ->subject($email->subject());
        $message->send();
    }
}