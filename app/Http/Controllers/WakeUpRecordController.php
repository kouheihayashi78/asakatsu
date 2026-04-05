<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWakeUpRecordRequest;
use App\Services\WakeUpRecordService;

class WakeUpRecordController extends Controller
{
    public function __construct(
        private WakeUpRecordService $wakeUpRecordService
    ) {}

    public function store(StoreWakeUpRecordRequest $request)
    {
        try {
            $user = $request->user();
            $this->wakeUpRecordService->recordWakeUp(
                $user->id,
                $user->target_wake_up_time
            );

            return back();
        } catch (\RuntimeException $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
