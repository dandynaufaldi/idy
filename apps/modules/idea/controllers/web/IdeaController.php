<?php

namespace Idy\Idea\Controllers\Web;

use Idy\Idea\Application\CreateNewIdeaRequest;
use Idy\Idea\Application\VoteIdeaRequest;
use Idy\Idea\Controllers\Validators\CreateNewIdeaValidator;
use Phalcon\Mvc\Controller;

class IdeaController extends Controller
{   
    private $allIdeasService;
    private $createNewIdeaService;
    private $voteIdeaService;

    public function initialize()
    {
        $this->allIdeasService = $this->di->get('view_all_ideas_service');
        $this->createNewIdeaService = $this->di->get('create_new_idea_service');
        $this->voteIdeaService = $this->di->get('vote_idea_service');
    }

    public function indexAction()
    {
        $service = $this->allIdeasService;
        $response = $service->execute();
        $error = $response->error();
        if ($error) {
            $this->flashSession->error($error);
        }
        $this->view->ideas = $response->ideas();
        return $this->view->pick('home');
    }

    public function addAction()
    {
        if (!$this->request->isPost()) {
            return $this->view->pick('add');
        }

        $validator = new CreateNewIdeaValidator();
        $messages = $validator->validate($_POST);
        if (count($messages)) {
            foreach ($messages as $message) {
                $this->flashSession->error($message->getMessage());
            }
            return $this->view->pick('add');        
        }
        $title = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $authorName = $this->request->getPost('author_name');
        $authorEmail = $this->request->getPost('author_email');
        $request = new CreateNewIdeaRequest(
            $title,
            $description,
            $authorName,
            $authorEmail
        );
        $response = $this->createNewIdeaService->execute($request);
        $error = $response->error();
        if ($error) {
            $this->flashSession->error($error);
            return $this->view->pick('add');
        }
        $this->flashSession->success($response->message());
        return $this->response->redirect('');
    }

    public function voteAction()
    {
        if (!$this->request->isPost()) {
            return $this->view->pick('vote');
        }
        $ideaId = $this->request->getPost('idea_id');
        $request = new VoteIdeaRequest($ideaId);
        $response = $this->voteIdeaService->execute($request);
        $error = $response->error();
        if ($error) {
            $this->flashSession->error($error);
            return $this->response->redirect('');
        }
        $this->flashSession->success($response->message());
        return $this->response->redirect('');
    }

    public function rateAction()
    {
        return $this->view->pick('rate');
    }

}