<?php

namespace Idy\Idea\Application;

use Exception;
use Idy\Idea\Domain\Model\Author;
use Idy\Idea\Domain\Model\Idea;
use Idy\Idea\Domain\Model\IdeaRepository;

class CreateNewIdeaService
{
    private $ideaRepository;

    public function __construct(IdeaRepository $ideaRepository)
    {
        $this->ideaRepository = $ideaRepository;
    }

    public function execute(CreateNewIdeaRequest $request)
    {
        $idea = Idea::makeIdea(
            $request->ideaTitle,
            $request->ideaDescription,
            new Author(
                $request->authorName, 
                $request->authorEmail
            )
        );
        try {
            $this->ideaRepository->save($idea);
            return new CreateNewIdeaResponse();
        } catch (Exception $e) {
            return new CreateNewIdeaResponse($e->getMessage());
        }
        
    }

}