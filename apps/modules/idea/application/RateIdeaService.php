<?php

namespace Idy\Idea\Application;

use Exception;
use Idy\Common\Exceptions\DuplicateItemException;
use Idy\Common\Exceptions\ResourceNotFoundException;
use Idy\Idea\Domain\Model\IdeaRepository;
use Idy\Idea\Domain\Model\RatingRepository;

class RateIdeaService
{
    private $ideaRepository;
    private $ratingRepository;

    public function __construct(
        IdeaRepository $ideaRepository,
        RatingRepository $ratingRepository
    )
    {
        $this->ideaRepository = $ideaRepository;
        $this->ratingRepository = $ratingRepository;
    }

    public function execute(RateIdeaRequest $request) : RateIdeaResponse
    {
        $idea = NULL;
        $ideaId = $request->ideaId();
        try {
            $idea = $this->ideaRepository->byId($ideaId);
        } catch (ResourceNotFoundException $e) {
            return new RateIdeaResponse($e->getMessage());
        }

        $rating = $request->rating();
        try {
            $idea->addRating($rating->user(), $rating->value());
        } catch (DuplicateItemException $e) {
            return new RateIdeaResponse($e->getMessage());
        }

        try {
            $this->ratingRepository->save($rating, $ideaId);
        } catch (Exception $e) {
            return new RateIdeaResponse('Fail to rate idea');
        }
        return new RateIdeaResponse();
    }
}