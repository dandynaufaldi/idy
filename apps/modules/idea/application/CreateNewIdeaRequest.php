<?php

namespace Idy\Idea\Application;

class CreateNewIdeaRequest
{
    public $ideaTitle;
    public $ideaDescription;
    public $authorName;
    public $authorEmail;

    public function __construct($ideaTitle, $ideaDescription, $authorName, $authorEmail)
    {
        $this->ideaTitle = $ideaTitle;
        $this->ideaDescription = $ideaDescription;
        $this->authorName = $authorName;
        $this->authorEmail = $authorEmail;
    }

}