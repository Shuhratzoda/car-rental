<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function render(string $template, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        $file = $this->basePath . '/' . $template . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("View not found: {$template}");
        }
        require $file;
        return (string) ob_get_clean();
    }

    public function renderLayout(string $contentTemplate, array $data = [], string $layout = 'layouts/main'): string
    {
        $data['content'] = $this->render($contentTemplate, $data);
        return $this->render($layout, $data);
    }
}
