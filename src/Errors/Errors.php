<?php

namespace UrlShortenerPhp\Errors;

use Exception;

class Errors
{
    public function render(int $type = 404)
    {
        $template = "{$this->path()}{$type}.html";
        renderApp($template, [
            "typeError" => $type,
        ]);
        die;
    }
    private function path()
    {
        $path = dirname(__FILE__, 2);
        return $path.DS."Pages".DS."Errors".DS;
    }
}
