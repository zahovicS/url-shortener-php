<?php

namespace App\Controllers;

use App\Models\UrlModel;
use UrlShortenerPhp\Base\Controller\BaseController;
use UrlShortenerPhp\Errors\Errors;

class UrlController extends BaseController
{
    protected string $url_redirect;
    protected UrlModel $urlModel;
    function __construct(string $url)
    {
        $this->url_redirect = $url;
        $this->urlModel = new UrlModel;
        parent::__construct("url/redirect");
    }
    public function redirectToUrl()
    {
        $exist = $this->urlModel->getBySlug($this->url_redirect);
        if (!$exist) {
            (new Errors)->render(404);
        }
        header("Location: {$exist->url}");
        exit;
    }
}
