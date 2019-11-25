<?php

namespace Idy\Idea\Application;

class RateIdeaResponse
{
    private $error;
    private $message;

    public function __construct($error = NULL, $message = NULL)
    {
        $this->error = $error;
        $this->message = $message ?? 'successfully rate idea';
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