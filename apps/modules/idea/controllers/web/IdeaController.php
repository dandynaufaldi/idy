<?php

namespace Idy\Idea\Controllers\Web;

use Phalcon\Mvc\Controller;
use Phalcon\Di;

class IdeaController extends Controller
{   
    private $allIdeasService;

    public function initialize()
    {
        $this->allIdeasService = Di::getDefault()->get('view_all_ideas_service');
    }

    public function indexAction()
    {
        $repository = Di::getDefault()->get('sql_idea_repository');
        $service = $this->allIdeasService;
        $response = $service->execute();
        if ($response->error())
        {
            $this->flashSession->error($response->error());
        }
        $this->view->ideas = $response->ideas();
        return $this->view->pick('home');
    }

    public function addAction()
    {
        return $this->view->pick('add');
    }

    public function voteAction()
    {
        return $this->view->pick('vote');
    }

    public function rateAction()
    {
        return $this->view->pick('rate');
    }

}