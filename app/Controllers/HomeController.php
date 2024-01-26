<?php

namespace App\Controllers;

use UrlShortenerPhp\Base\Controller\BaseController;

class HomeController extends BaseController
{
    function __construct(string $url)
    {
        parent::__construct($url);
    }
    public function index()
    {
        return view("Home.index");
    }
}
