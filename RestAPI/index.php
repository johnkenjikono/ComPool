<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . "/inc/bootstrap.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Check that both controller and method are present
if (!isset($uri[2]) || !isset($uri[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// e.g., $controller = "user" or "group"
$controllerName = ucfirst($uri[2]) . "Controller";
$controllerPath = PROJECT_ROOT_PATH . "/Controller/Api/" . $controllerName . ".php";

if (!file_exists($controllerPath)) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

require $controllerPath;
$objController = new $controllerName();
$methodName = $uri[3] . "Action";

if (!method_exists($objController, $methodName)) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$objController->{$methodName}();
