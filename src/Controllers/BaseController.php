<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

abstract class BaseController
{
    protected View $view;

    public function __construct()
    {
        $this->view = new View(BASE_PATH . '/views');
    }

    protected function render(string $template, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        $data['user']  = current_user();
        $data['flash'] = flash();
        echo $this->view->renderLayout($template, $data);
    }

    protected function redirect(string $url): never
    {
        redirect($url);
    }
}
