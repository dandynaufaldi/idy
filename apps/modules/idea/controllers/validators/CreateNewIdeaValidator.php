<?php

namespace Idy\Idea\Controllers\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class CreateNewIdeaValidator extends Validation
{
    public function initialize() 
    {
        $this->add(
            [
                'title',
                'description',
                'author_name',
                'author_email'
            ],
            new PresenceOf(
                [
                    'message' => [
                        'title' => 'Title is required',
                        'description' => 'Description is required',
                        'author_name' => 'Your name is required',
                        'author_email' => 'Your email is required'
                    ], 
                ]
            ) 
        );
        $this->add(
            'author_email',
            new Email(
                ['message' => 'Email format is invalid']
            )
        );
    }
}