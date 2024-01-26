<?php

namespace UrlShortenerPhp\Base\Controller;

use UrlShortenerPhp\Auth\Auth;

class BaseController extends Auth
{
    function __construct(string $url)
    {
        parent::__construct($url);
    }
    public function existsPOST(array $params)
    {
        foreach ($params as $param) {
            if (!isset($_POST[$param])) {
                return false;
            }
            if (empty($_POST[$param])){
                return false;
            }
        }
        return true;
    }

    public function getPost(string $param)
    {
        return $_POST[$param] && !empty($_POST[$param]) ? $_POST[$param] : null;
    }

    public function existsGET(array $params)
    {
        foreach ($params as $param) {
            if (!isset($_GET[$param])) {
                return false;
            }
        }
        return true;
    }
    public function getGet(string $param)
    {
        return $_GET[$param] ?? null;
    }
}
