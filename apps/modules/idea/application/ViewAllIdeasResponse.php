<?php

namespace Idy\Idea\Application;


class ViewAllIdeasResponse
{
    private $ideas;
    private $error;
    
    public function __construct($ideas = NULL, $error = NULL)
    {
        $this->ideas = $ideas;
        $this->error = $error;
    }

    public function ideas()
    {
        return $this->ideas;
    }

    public function error()
    {
        return $this->error;
    }
}