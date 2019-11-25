<?php

namespace Idy\Idea\Controllers\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class RateIdeaValidator extends Validation
{
    public function initialize()
    {
        $this->add(
            [
                'name',
                'value',
            ],
            new PresenceOf(
                [
                    'message' => [
                        'name' => 'Name is required',
                        'value' => 'Rating value is required',
                    ], 
                ]
            ) 
        );
    }
}