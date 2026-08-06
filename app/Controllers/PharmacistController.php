<?php

declare(strict_types=1);

class PharmacistController extends Controller
{
    public function __construct()
    {
        require_staff_login('pharmacist');
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->view('pharmacist/dashboard');
    }

    public function dispense(): void
    {
        $this->view('pharmacist/dispense');
    }

    private function readRequestData(): array
    {
        $input = $_POST;
        if (empty($input)) {
            $raw = file_get_contents('php://input');
            if ($raw !== false && $raw !== '') {
                $json = json_decode($raw, true);
                if (is_array($json)) {
                    $input = $json;
                }
            }
        }
        return $input;
    }

    private function normalizeReason(string $raw): string
    {
        $lower = strtolower($raw);
        if (str_contains($lower, 'damage')) {
            return 'damaged';
        }
        if (str_contains($lower, 'baseline') || str_contains($lower, 'intake')) {
            return 'baseline_intake';
        }
        if (str_contains($lower, 'return')) {
            return 'order_return';
        }
        return 'audit_correction';
    }

    private function jsonResponse(array $payload, int $status = 200): void
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || str_contains($accept, 'application/json');

        if (!$isAjax && isset($_POST['redirect_back'])) {
            if ($payload['ok'] ?? false) {
                flash_success($payload['message'] ?? 'Action completed');
            } else {
                flash_error($payload['error'] ?? 'An error occurred');
            }
            $this->redirect('/staff/pharmacist/inventory');
        }

        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }
}
