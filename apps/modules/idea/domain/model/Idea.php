<?php

namespace Idy\Idea\Domain\Model;

use Idy\Common\Events\DomainEventPublisher;
use Idy\Common\Exceptions\DuplicateItemException;
use Idy\Common\Exceptions\InvalidArgumentException;

class Idea
{
    private $id;
    private $title;
    private $description;
    private $author;
    private $ratings;
    private $votes;
    
    public function __construct(
        IdeaId $id, 
        string $title, 
        string $description, 
        Author $author, 
        int $votes, 
        array $ratings
        )
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
        $this->ratings = $ratings;
        $this->votes = $votes;
    }

    public function id() 
    {
        return $this->id;
    }

    public function title()
    {
        return $this->title;
    }

    public function description()
    {
        return $this->description;
    }

    public function author()
    {
        return $this->author;
    }

    public function votes()
    {
        return $this->votes;
    }

    public function addRating($user, $ratingValue)
    {
        $newRating = new Rating($user, $ratingValue);
        if (!$newRating->isValid()) {
            throw new InvalidArgumentException('Rating value exceed boundary');
        }
        $exist = false;
        foreach ($this->ratings as $existingRating) {
            if ($existingRating->equals($newRating)) {
                $exist = true;
            }
        }

        if (!$exist) {
            array_push($this->ratings, $newRating);
        } else {
            throw new DuplicateItemException('Author ' . $newRating->author() . ' has given a rating.');
        }

        DomainEventPublisher::instance()->publish(
            new IdeaRated($this->author->name(), $this->author->email(), 
                $this->title, $ratingValue)
        );

    }

    public function vote()
    {   
        $this->votes = $this->votes + 1;
    }

    public function averageRating()
    {
        $totalRatings = 0;
        $numberOfRatings = count($this->ratings);
        if ($numberOfRatings == 0) {
            return 0.0;
        }
        
        foreach ($this->ratings as $rating) {
            $totalRatings += $rating->value();
        }

        return $totalRatings / $numberOfRatings;
    }

    public static function makeIdea($title, $description, $author)
    {
        $newIdea = new Idea(new IdeaId(), $title, $description, $author, 0, array());
        
        return $newIdea;
    }

}