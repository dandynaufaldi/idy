<?php

namespace Idy\Idea\Application;

use Idy\Common\Events\DomainEventSubscriber;
use Idy\Idea\Domain\Model\Email;
use Idy\Idea\Domain\Model\IdeaRated;
use Idy\Idea\Domain\Model\Mailer;

class SendRatingNotificationService implements DomainEventSubscriber
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle($aDomainEvent)
    {
        $email = Email::fromIdeaRated($aDomainEvent);
        $this->mailer->send($email);
    }

    public function isSubscribedTo($aDomainEvent)
    {
        return $aDomainEvent instanceof IdeaRated;
    }
}
