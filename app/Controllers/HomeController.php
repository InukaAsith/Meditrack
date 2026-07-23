<?php
declare(strict_types=1);

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('landing');
    }
}
