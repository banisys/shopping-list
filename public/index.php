<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Request;

$router = new Router(new Request());
$router->resolve();
