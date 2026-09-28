<?php

declare(strict_types=1);

class GuestController extends Controller
{
    public function book(): void
    {
        if (signed_in_patient_id() !== null) {
            $query = (string) ($_SERVER['QUERY_STRING'] ?? '');
            $this->redirect('/app/book' . ($query !== '' ? '?' . $query : ''));
        }
        $this->view('guest/book', BookingCatalogue::build());
    }

    public function track(string $code = ''): void
    {
        $this->view('guest/tracking');
    }
}
