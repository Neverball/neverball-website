<?php
const IN_APP = true;

require_once __DIR__ . '/config.php';

global $current_route;

$route = isset($_GET['route']) ? rtrim($_GET['route'], '/') : '';

unset($_GET['route']);

$routes = [
    '' => CONTROLLER_DIR . '/home.php',
    'download' => CONTROLLER_DIR . '/download.php',
    'credits' => CONTROLLER_DIR . '/contributors.php',
    'release' => CONTROLLER_DIR . '/release.php',
    'screenshots' => CONTROLLER_DIR . '/screenshots.php',
    'changes' => CONTROLLER_DIR . '/changes.php',
];

if (isset($routes[$route])) {
	$current_route = $route;
	$controller = $routes[$route];
} else {
	http_response_code(404);
	$controller = CONTROLLER_DIR . '/404.php';
}

require_once $controller;