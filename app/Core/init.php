<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Colombo');

require __DIR__ . '/../../config/database.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/Controller.php';
require __DIR__ . '/App.php';

spl_autoload_register(function (string $className): void {
    foreach (['Controllers', 'Models'] as $folder) {
        $file = __DIR__ . '/../' . $folder . '/' . $className . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});
