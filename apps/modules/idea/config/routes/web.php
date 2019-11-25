<?php

$namespace = 'Idy\Idea\Controllers\Web';

$router->addGet('/', [
    'namespace' => $namespace,
    'module' => 'idea',
    'controller' => 'idea',
    'action' => 'index'
]);

$router->add('/idea/add', [
    'namespace' => $namespace,
    'module' => 'idea',
    'controller' => 'idea',
    'action' => 'add'
]);

$router->addPost('/idea/vote', [
    'namespace' => $namespace,
    'module' => 'idea',
    'controller' => 'idea',
    'action' => 'vote'
]);

$router->add('/idea/rate/:params', [
    'namespace' => $namespace,
    'module' => 'idea',
    'controller' => 'idea',
    'action' => 'rate'
]);