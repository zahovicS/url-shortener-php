<?php

namespace UrlShortenerPhp\Application;

use Dotenv\Dotenv;
use UrlShortenerPhp\Router\Router;

class Application
{
    private string $app_path;
    function __construct(string $app_path)
    {
        $this->app_path = $app_path;
    }
    public function init(): void
    {
        $this->loadENV();
        $this->loadGlobals();
        $this->loadHelpers();
        $this->loadErrors();
    }
    public function resolveRequest()
    {
        $url = $_GET["url"] ?? "Home";
        $router = new Router();
        $router->init($url);
        $router->run();
    }
    private function loadENV(): void
    {
        $dotenv = Dotenv::createImmutable($this->app_path);
        $dotenv->load();
    }
    private function loadGlobals(): void
    {
        define("DS", DIRECTORY_SEPARATOR);
        define("APP_PATH", $this->app_path);
        define("APP_VIEWS", $this->app_path . DS . "app" . DS . "Views" . DS);
        define("PUBLIC_ASSETS", "{$_ENV["APP_URL"]}public/assets/");
        define("APP_URL", $_ENV["APP_URL"]);
    }
    private function loadErrors(): void
    {
        $activateErrors = boolval($_ENV['APP_DEBUG']) ?? false;
        if ($activateErrors) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
    }
    private function getHelpersPath(): string
    {
        return $this->app_path . DS . "src" . DS . "Helpers" . DS;
    }
    private function loadHelpers()
    {
        foreach (glob("{$this->getHelpersPath()}*.php") as $filename) {
            if (file_exists($filename)) {
                require_once $filename;
            }
        };
    }
}
