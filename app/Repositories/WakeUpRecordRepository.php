<?php

namespace App\Repositories;

use App\Models\WakeUpRecord;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WakeUpRecordRepository
{
    /**
     * 指定ユーザーの今日の起床記録を取得
     */
    public function getTodayRecordByUser(int $userId): ?WakeUpRecord
    {
        return WakeUpRecord::where('user_id', $userId)
            ->whereDate('recorded_at', Carbon::today())
            ->first();
    }

    /**
     * 指定ユーザーの今日の記録が存在するか確認
     */
    public function existsTodayRecord(int $userId): bool
    {
        return WakeUpRecord::where('user_id', $userId)
            ->whereDate('recorded_at', Carbon::today())
            ->exists();
    }

    /**
     * 指定ユーザーの直近N日分の記録を取得
     */
    public function getRecentRecordsByUser(int $userId, int $days = 30): Collection
    {
        return WakeUpRecord::where('user_id', $userId)
            ->where('recorded_at', '>=', Carbon::now()->subDays($days))
            ->orderBy('recorded_at', 'desc')
            ->get();
    }

    /**
     * 起床記録を作成
     */
    public function create(int $userId, Carbon $recordedAt, bool $isAchieved): WakeUpRecord
    {
        return WakeUpRecord::create([
            'user_id' => $userId,
            'recorded_at' => $recordedAt,
            'is_achieved' => $isAchieved,
        ]);
    }
}
