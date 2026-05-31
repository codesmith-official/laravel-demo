<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class DashboardStatsController extends Controller
{
    public function __construct(private readonly UserRepository $users) {}

    public function __invoke(): JsonResponse
    {
        return response()->json([
            'total_users' => $this->users->activeCount(),
        ]);
    }
}
