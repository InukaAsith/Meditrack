<?php
declare(strict_types=1);

if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . urldecode((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (is_file($file)) {
        return false;
    }
}

const REMEMBER_ME_SECONDS = 30 * 24 * 60 * 60;
ini_set('session.gc_maxlifetime', (string) REMEMBER_ME_SECONDS);

session_start();
require __DIR__ . '/../app/Core/init.php';

$app = new App();
$app->run();
