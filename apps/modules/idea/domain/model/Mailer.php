<?php

namespace Idy\Idea\Domain\Model;

interface Mailer
{
    public function send(Email $email) : void;
}