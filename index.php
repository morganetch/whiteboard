<?php
session_start();

define('DS', DIRECTORY_SEPARATOR);
define('WWW_ROOT', __DIR__ . DS);

$routes = array(
    'home' => array(
        'controller' => 'Pages',
        'action' => 'home'
    ),
    'register' => array(
        'controller' => 'Users',
        'action' => 'register'
    ),
    'login' => array(
        'controller' => 'Users',
        'action' => 'login'
    ),
    'logout' => array(
        'controller' => 'Users',
        'action' => 'logout'
    ),
    'overview' => array(
        'controller' => 'Boards',
        'action' => 'overview'
    ),
    'view' => array(
        'controller' => 'Boards',
        'action' => 'view'
    ),
     'add' => array(
        'controller' => 'Boards',
        'action' => 'add'
    ),
     'save' => array(
        'controller' => 'Boards',
        'action' => 'save'
    )
);

if(empty($_GET['page'])) {
    $_GET['page'] = 'home';
}
if(empty($routes[$_GET['page']])) {
    header('Location: index.php');
    exit();
}

$route = $routes[$_GET['page']];
$controllerName = $route['controller'] . 'Controller';

require_once WWW_ROOT . 'controller' . DS . $controllerName . ".php";

$controllerObj = new $controllerName();
$controllerObj->route = $route;
$controllerObj->filter();
$controllerObj->render();