<?php

namespace Idy\Idea\Domain\Model;

interface IdeaRepository
{
    public function byId(IdeaId $id) : ?Idea;
    public function save(Idea $idea) : void;
    public function allIdeas() : ?array;
}