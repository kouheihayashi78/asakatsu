<?php

namespace App\Services;

use App\Repositories\WakeUpRecordRepository;
use App\Repositories\UserRepository;
use App\Models\WakeUpRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WakeUpRecordService
{
    public function __construct(
        private WakeUpRecordRepository $wakeUpRecordRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * 起床記録を登録
     *
     * @throws \RuntimeException 既に記録済みの場合
     */
    public function recordWakeUp(int $userId, ?string $targetWakeUpTime = null): WakeUpRecord
    {
        // 重複チェック
        if ($this->wakeUpRecordRepository->existsTodayRecord($userId)) {
            throw new \RuntimeException('本日の起床記録はすでに登録されています。');
        }

        $now = Carbon::now();
        $isAchieved = $this->checkAchievement($now, $targetWakeUpTime);

        // 達成回数のインクリメントとレコード作成をトランザクションで原子的に実行
        return DB::transaction(function () use ($userId, $now, $isAchieved) {
            if ($isAchieved) {
                $this->userRepository->incrementAchievements($userId);
            }

            return $this->wakeUpRecordRepository->create($userId, $now, $isAchieved);
        });
    }

    /**
     * 目標達成判定
     */
    private function checkAchievement(Carbon $recordedTime, ?string $targetWakeUpTime): bool
    {
        if (!$targetWakeUpTime) {
            return false;
        }

        $targetTime = Carbon::createFromTimeString($targetWakeUpTime);
        return $recordedTime->format('H:i:s') <= $targetTime->format('H:i:s');
    }

    /**
     * ユーザーのダッシュボードデータを取得
     */
    public function getDashboardData(int $userId): array
    {
        return [
            'todayRecord' => $this->wakeUpRecordRepository->getTodayRecordByUser($userId),
            'recentRecords' => $this->wakeUpRecordRepository->getRecentRecordsByUser($userId, 30),
        ];
    }
}
