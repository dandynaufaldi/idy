<?php

namespace Idy\Idea\Application;

use Exception;
use Idy\Idea\Domain\Model\IdeaRepository;

class ViewAllIdeasService
{
    private $repository;

    public function __construct(IdeaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute()
    {
        try {
            $ideas = $this->repository->allIdeas();
            return new ViewAllIdeasResponse($ideas);
        } catch (Exception $e) {
            return new ViewAllIdeasResponse(NULL, 'Internal server error');
        }
        
    }
}