<?php

namespace UrlShortenerPhp\Router;

use Error;
use Exception;
use UrlShortenerPhp\Errors\Errors;
use UrlShortenerPhp\Session\Session;

class Router
{
    private string $ogController;
    private string $controller;
    private string $method;
    private $params;
    public function init($url)
    {
        $URI = $this->splitURL($url);
        $controller = ucfirst($URI[0]);
        $this->ogController = $controller;
        $this->controller = "{$controller}Controller";
        $this->method = $URI[1] ?? "index";
        $this->params = $URI[2] ?? null;
    }
    private function splitURL($url): array
    {
        return explode("/", $url);
    }
    public function run()
    {
        $controllerClass = 'App\\Controllers\\' . $this->controller;
        if (class_exists($controllerClass)) {
            $_url = strtolower("{$this->ogController}/{$this->method}"); 
            if($this->ogController == "Url"){
                $controller = new $controllerClass($this->method);
                $controller->redirectToUrl();
            }else{
                $controller = new $controllerClass($_url);
                if ($this->params) {
                    $controller->{$this->method}($this->params);
                }else{
                    $controller->{$this->method}();
                }
                Session::unflash();
            }
        } else {
            (new Errors)->render(404);
        }
    }
}
