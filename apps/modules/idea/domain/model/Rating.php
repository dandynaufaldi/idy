<?php

namespace Idy\Idea\Domain\Model;

class Rating
{
    private $user;
    private $value;

    public function __construct($user, $value) 
    {
        $this->user = $user;
        $this->value = $value;
    }

    public function user()
    {
        return $this->user;
    }

    public function value()
    {
        return $this->value;
    }

    public function equals(Rating $rating) 
    {
        return $this->user === $rating->user() && 
                $this->value === $rating->value();
    }

    public function isValid() 
    {
        if ($this->user && $this->value && $this->value > 0 && $this->value <= 5) {
            return true;
        }
        return false;
    }

}