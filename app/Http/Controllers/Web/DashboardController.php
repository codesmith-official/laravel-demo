<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly UserRepository $users) {}

    public function __invoke(): View
    {
        return view('dashboard.index', [
            'totalUsers' => $this->users->activeCount(),
        ]);
    }
}
