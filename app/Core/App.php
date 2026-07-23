<?php

declare(strict_types=1);

class App
{
    private array $pages = [
        '/'                => ['HomeController', 'index'],
        '/login'           => ['AuthController', 'login'],
        '/signin'          => ['AuthController', 'login'],
        '/forgot-password' => ['AuthController', 'login'],
        '/register'        => ['AuthController', 'register'],
        '/otp'             => ['AuthController', 'otp'],
        '/change-password' => ['AuthController', 'changePassword'],
        '/logout'          => ['AuthController', 'logout'],
        '/book'            => ['GuestController', 'book'],
    ];

    private array $sections = [
        '/app'                => 'PatientController',
        '/guest'              => 'GuestController',
        '/staff/admin'        => 'AdminController',
        '/staff/doctor'       => 'DoctorController',
        '/staff/manager'      => 'ManagerController',
        '/staff/pharmacist'   => 'PharmacistController',
        '/staff/receptionist' => 'ReceptionistController',
        '/staff/supporting'   => 'SupportingController',
        '/staff'              => 'StaffAuthController',
    ];

    public function run(): void
    {
        $path = rtrim((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        if ($path === '') {
            $path = '/';
        }

        if (isset($this->pages[$path])) {
            $controllerName = $this->pages[$path][0];
            $method = $this->pages[$path][1];
            $controller = new $controllerName();
            $controller->$method();
            return;
        }

        foreach ($this->sections as $prefix => $controllerName) {
            if ($path !== $prefix && !str_starts_with($path, $prefix . '/')) {
                continue;
            }

            $rest = trim(substr($path, strlen($prefix)), '/');
            $method = 'index';
            $arguments = [];
            if ($rest !== '') {
                $parts = explode('/', $rest);
                $method = $this->toMethodName(array_shift($parts));
                $arguments = $parts;
            }

            $controller = new $controllerName();
            if (!is_callable([$controller, $method]) || str_starts_with($method, '_')) {
                $controller->notFound();
            }
            $controller->$method(...$arguments);
            return;
        }

        (new HomeController())->notFound();
    }

    private function toMethodName(string $urlPart): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $urlPart))));
    }
}
