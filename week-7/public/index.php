<?php

const BASE_PATH = __DIR__ . '/../';
require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . 'functions.php';
require BASE_PATH . 'Core/router.php';

use Core\Router;

$router = new Router();
$routes = require base_path('routes.php');
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$router->route($uri, $method);
