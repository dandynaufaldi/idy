<?php

namespace Idy\Idea\Application;

use Idy\Idea\Domain\Model\IdeaId;

class VoteIdeaRequest
{
    private $ideaId;

    public function __construct($ideaId)
    {
        $this->ideaId = new IdeaId($ideaId);
    }

    public function ideaId() : IdeaId
    {
        return $this->ideaId;
    }
}