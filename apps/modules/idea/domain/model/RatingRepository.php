<?php

namespace Idy\Idea\Domain\Model;

interface RatingRepository
{
    public function byIdeaId(IdeaId $id) : array;
    public function save(Rating $rating, IdeaId $ideaId) : void;
}