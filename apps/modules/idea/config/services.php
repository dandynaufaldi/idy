<?php

use Idy\Common\Events\DomainEventPublisher;
use Idy\Idea\Application\CreateNewIdeaService;
use Idy\Idea\Application\RateIdeaService;
use Idy\Idea\Application\SendRatingNotificationService;
use Idy\Idea\Application\ViewAllIdeasService;
use Idy\Idea\Application\VoteIdeaService;
use Idy\Idea\Infrastructure\SmtpMailer;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;
use Idy\Idea\Infrastructure\SqlIdeaRepository;
use Idy\Idea\Infrastructure\SqlRatingRepository;

$di['voltServiceMail'] = function($view) use ($di) {

    $config = $di->get('config');

    $volt = new Volt($view, $di);
    if (!is_dir($config->mail->cacheDir)) {
        mkdir($config->mail->cacheDir);
    }

    $compileAlways = $config->mode == 'DEVELOPMENT' ? true : false;

    $volt->setOptions(array(
        "compiledPath" => $config->mail->cacheDir,
        "compiledExtension" => ".compiled",
        "compileAlways" => $compileAlways
    ));
    return $volt;
};

$di['view'] = function () {
    $view = new View();
    $view->setViewsDir(__DIR__ . '/../views/');

    $view->registerEngines(
        [
            ".volt" => "voltService",
        ]
    );

    return $view;
};

$di['db'] = function () use ($di) {

    $config = $di->get('config');

    $dbAdapter = $config->database->adapter;

    return new $dbAdapter([
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->dbname
    ]);
};

$di->setShared('sql_idea_repository', function() use ($di) {
    $repo = new SqlIdeaRepository($di);
    return $repo;
});

$di->setShared('sql_rating_repository', function() use ($di) {
    $repo = new SqlRatingRepository($di);
    return $repo;
});

$di->set('view_all_ideas_service', function() use ($di){
    $repo = $di->get('sql_idea_repository');
    $service = new ViewAllIdeasService($repo);
    return $service;
});

$di->set('create_new_idea_service', function() use ($di){
    $repo = $di->get('sql_idea_repository');
    $service = new CreateNewIdeaService($repo);
    return $service;
});

$di->set('vote_idea_service', function() use ($di){
    $repo = $di->get('sql_idea_repository');
    $service = new VoteIdeaService($repo);
    return $service;
});

$di->set('rate_idea_service', function() use ($di){
    $ideaRepo = $di->get('sql_idea_repository');
    $ratingRepo = $di->get('sql_rating_repository');
    $service = new RateIdeaService($ideaRepo, $ratingRepo);
    return $service;
});

$di->setShared('smtp_mailer', function () use ($di) {
    $mailer = new SmtpMailer($di);
    return $mailer;
});

$di->setShared('send_rating_notif_service', function () use ($di){
    $mailer = $di->get('smtp_mailer');
    $sendRatingNotifService = new SendRatingNotificationService(
        $mailer
    );
    return $sendRatingNotifService;
});