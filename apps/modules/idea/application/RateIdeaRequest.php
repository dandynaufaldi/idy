<?php

namespace Idy\Idea\Application;

use Idy\Idea\Domain\Model\IdeaId;
use Idy\Idea\Domain\Model\Rating;

class RateIdeaRequest
{
    private $ideaId;
    private $rating;

    public function __construct($ideaId, $user, $value)
    {
        $this->ideaId = new IdeaId($ideaId);
        $this->rating = new Rating($user, $value);
    }

    public function ideaId() : IdeaId
    {
        return $this->ideaId;
    }

    public function rating() : Rating
    {
        return $this->rating;
    }
}