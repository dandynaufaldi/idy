<?php

namespace Idy\Idea\Domain\Model;

class Email
{
    private $subject;
    private $body;
    private $recipient;
    private $params;

    public function __construct($subject, $recipient, $body = NULL, $params = NULL)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipient = $recipient;
        $this->params = $params;
    }

    public function subject()
    {
        return $this->subject;
    }

    public function body()
    {
        return $this->body;
    }

    public function recipient()
    {
        return $this->recipient;
    }

    public function params()
    {
        return $this->params;
    }

    public static function fromIdeaRated(IdeaRated $ideaRated) : Email
    {
        return new Email(
            'New Rating Received',
            $ideaRated->email(),
            NULL,
            [
                'name' => $ideaRated->name(),
                'title' => $ideaRated->title(),
                'rating' => $ideaRated->rating()
            ]
        );
    }
}