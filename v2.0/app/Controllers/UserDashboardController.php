<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class UserDashboardController extends Controller
{
    public function index(): void
    {
        $this->view('user.dashboard');
    }
}
