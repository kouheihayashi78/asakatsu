<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\WakeUpRecordService;

class DashboardController extends Controller
{
    public function __construct(
        private WakeUpRecordService $wakeUpRecordService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $dashboardData = $this->wakeUpRecordService->getDashboardData($user->id);

        return Inertia::render('Dashboard', array_merge($dashboardData, [
            'user' => $user
        ]));
    }
}
