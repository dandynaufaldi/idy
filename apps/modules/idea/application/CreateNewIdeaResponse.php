<?php

namespace Idy\Idea\Application;

class CreateNewIdeaResponse
{
    private $error;

    public function __construct($error = NULL)
    {
        $this->error = $error;
    }

    public function error()
    {
        return $this->error;
    }

}