<?php

declare(strict_types=1);

class Controller
{
    protected function view(string $name, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $name . '.php';
    }

    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    public function notFound(): never
    {
        http_response_code(404);
        require __DIR__ . '/../Views/errors/404.php';
        exit;
    }
}
