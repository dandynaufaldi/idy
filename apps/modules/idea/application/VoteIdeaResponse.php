<?php

namespace Idy\Idea\Application;

class VoteIdeaResponse
{
    private $error;
    private $message;

    public function __construct($error = NULL, $message = NULL)
    {
        $this->error = $error;
        $this->message = $message ?? 'successfully vote idea';
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