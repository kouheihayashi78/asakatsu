<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class WakeUpRecordController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now();

        // 今日すでに登録しているかチェック
        $alreadyRecorded = $user->wakeUpRecords()
            ->whereDate('recorded_at', Carbon::today())
            ->exists();

        if ($alreadyRecorded) {
            return back()->withErrors(['message' => '本日の起床記録はすでに登録されています。']);
        }

        $isAchieved = false;
        if ($user->target_wake_up_time) {
            $targetTime = Carbon::createFromTimeString($user->target_wake_up_time);
            // 目標時間より前に起きたか判定
            if ($now->format('H:i:s') <= $targetTime->format('H:i:s')) {
                $isAchieved = true;
                $user->increment('wake_up_achievements');
            }
        }

        $user->wakeUpRecords()->create([
            'recorded_at' => $now,
            'is_achieved' => $isAchieved,
        ]);

        return back();
    }
}
