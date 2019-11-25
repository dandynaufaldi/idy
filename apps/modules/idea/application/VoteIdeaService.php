<?php

namespace Idy\Idea\Application;

use Exception;
use Idy\Common\Exceptions\ResourceNotFoundException;
use Idy\Idea\Domain\Model\IdeaRepository;

class VoteIdeaService
{
    private $ideaRepository;

    public function __construct(IdeaRepository $ideaRepository)
    {
        $this->ideaRepository = $ideaRepository;
    }

    public function execute(VoteIdeaRequest $request)
    {
        $ideaId = $request->ideaId();
        try {
            $idea = $this->ideaRepository->byId($ideaId);
            $idea->vote();
            $this->ideaRepository->save($idea);
        } catch (ResourceNotFoundException $e) {
            return new VoteIdeaResponse($e->getMessage());
        } catch (Exception $e) {
            return new VoteIdeaResponse('Failed to vote idea');
        }
        return new VoteIdeaResponse();
        
    }
}