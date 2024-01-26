<?php
use UrlShortenerPhp\Application\Application;

require_once "./vendor/autoload.php";

$app = new Application(__DIR__);

$app->init();

$app->resolveRequest();

// var_dump($_GET);