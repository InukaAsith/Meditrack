<?php

declare(strict_types=1);

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_check(): bool
{
    $sent = $_POST['csrf_token'] ?? '';
    if ($sent === '' && !empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $sent = (string) $_SERVER['HTTP_X_CSRF_TOKEN'];
    }
    if ($sent === '') {
        $raw = file_get_contents('php://input');
        if ($raw !== false && $raw !== '') {
            $json = json_decode($raw, true);
            if (is_array($json) && isset($json['csrf_token'])) {
                $sent = (string) $json['csrf_token'];
            }
        }
    }
    $expected = $_SESSION['csrf_token'] ?? '';

    return $expected !== '' && hash_equals($expected, $sent);
}

function flash_error(string $message): void
{
    $_SESSION['flash_error'] = $message;
}

function get_flash_error(): ?string
{
    $message = $_SESSION['flash_error'] ?? null;
    unset($_SESSION['flash_error']);

    return $message;
}

function flash_success(string $message): void
{
    $_SESSION['flash_success'] = $message;
}

function get_flash_success(): ?string
{
    $message = $_SESSION['flash_success'] ?? null;
    unset($_SESSION['flash_success']);

    return $message;
}

function password_rule_error(string $password, string $confirm): ?string
{
    if (strlen($password) < 8) {
        return 'Your password needs at least 8 characters.';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return 'Your password needs at least one uppercase letter.';
    }
    if (!preg_match('/[a-z]/', $password)) {
        return 'Your password needs at least one lowercase letter.';
    }
    if (!preg_match('/[0-9]/', $password)) {
        return 'Your password needs at least one number.';
    }
    if ($password !== $confirm) {
        return 'Those two passwords do not match.';
    }

    return null;
}

const STAFF_ROLE_SLUGS = [
    'Doctor'           => 'doctor',
    'Receptionist'     => 'receptionist',
    'Supporting Staff' => 'supporting',
    'Pharmacist'       => 'pharmacist',
    'Manager'          => 'manager',
    'Admin'            => 'admin',
];

function staff_role_slug(string $roleName): string
{
    return STAFF_ROLE_SLUGS[$roleName] ?? '';
}

function current_patient_id(): ?int
{
    return isset($_SESSION['patient_id']) ? (int) $_SESSION['patient_id'] : null;
}

function current_staff_id(): ?int
{
    return isset($_SESSION['staff_id']) ? (int) $_SESSION['staff_id'] : null;
}

function current_staff_role(): ?string
{
    return $_SESSION['staff_role'] ?? null;
}

const TRUSTED_DEVICE_COOKIE = 'staff_device';

function trusted_device_token(): string
{
    return (string) ($_COOKIE[TRUSTED_DEVICE_COOKIE] ?? '');
}

function current_device_is_trusted(): bool
{
    $staffId = current_staff_id();

    return $staffId !== null && StaffTrustedDevice::isTrusted($staffId, trusted_device_token());
}

function require_patient_login(): void
{
    if (current_patient_id() !== null && Patient::find(current_patient_id()) === false) {
        $_SESSION = [];
        session_regenerate_id(true);
        header('Location: /login');
        exit;
    }
    if (current_patient_id() !== null) {
        no_cache_headers();
        return;
    }
    if (current_staff_id() !== null) {
        show_no_access();
    }

    header('Location: /login');
    exit;
}

function require_staff_login(string $expectedSlug): void
{
    if (current_staff_id() !== null && !Staff::isActive(current_staff_id())) {
        $_SESSION = [];
        session_regenerate_id(true);
        flash_error('This account has been deactivated. Contact your administrator.');
        header('Location: /staff/login');
        exit;
    }

    if (current_staff_id() !== null && current_staff_role() === $expectedSlug) {
        no_cache_headers();
        return;
    }
    if (current_staff_id() !== null || current_patient_id() !== null) {
        show_no_access();
    }

    header('Location: /staff/login');
    exit;
}

function no_cache_headers(): void
{
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

function show_no_access(): never
{
    http_response_code(403);
    no_cache_headers();
    require __DIR__ . '/../Views/errors/403.php';
    exit;
}
