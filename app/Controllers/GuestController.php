<?php

declare(strict_types=1);

class GuestController extends Controller
{
    public function book(): void
    {
        $this->view('guest/book', BookingCatalogue::build());
    }

    public function track(string $code = ''): void
    {
        $this->view('guest/tracking');
    }
}
