<?php

namespace Idy\Idea\Application;

class CreateNewIdeaResponse
{
    private $error;
    private $message;

    public function __construct($error = NULL, $message = NULL
    )
    {
        $this->error = $error;
        $this->message = $message ?? 'Idea created';
    }

    public function error()
    {
        return $this->error;
    }

    public function message()
    {
        return $this->message;
    }

}