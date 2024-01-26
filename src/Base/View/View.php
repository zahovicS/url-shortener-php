<?php

namespace UrlShortenerPhp\Base\View;

use Exception;

class View
{
    public function render(string $template, array $data = [])
    {
        $newTemplate = APP_VIEWS . str_replace(".",DS,$template) . ".php";
        $this->loadView($newTemplate, $data);
    }
    public function renderInApp(string $template, array $data = [])
    {
        $this->loadView($template, $data);
    }
    private function loadView(string $template, array $data = []): void
    {
        extract($data);
        if (!file_exists($template)) {
            throw new Exception("View not found", 1);
        }
        require $template;
    }
}
