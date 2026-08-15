<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    /**
     * 指定ユーザー以外のクエリを取得
     */
    public function queryExcludingUser(int $excludeUserId): Builder
    {
        return User::where('id', '!=', $excludeUserId)
            ->with(['wakeUpRecords' => function ($query) {
                $query->whereDate('recorded_at', Carbon::today());
            }]);
    }

    /**
     * 同じ目標時間帯（1時間単位）のユーザーでフィルタ
     */
    public function filterBySameTargetHour(Builder $query, string $targetTime): Builder
    {
        $startOfHour = Carbon::createFromTimeString($targetTime)->startOfHour();
        $endOfHour = $startOfHour->copy()->endOfHour();

        return $query->whereBetween('target_wake_up_time', [
            $startOfHour->format('H:i:s'),
            $endOfHour->format('H:i:s'),
        ]);
    }

    /**
     * フォロー中のユーザーでフィルタ
     */
    public function filterByFollowing(Builder $query, int $followerId): Builder
    {
        return $query->whereHas('followers', function ($q) use ($followerId) {
            $q->where('follower_id', $followerId);
        });
    }

    /**
     * 達成回数順でページネーション
     */
    public function paginateByAchievements(Builder $query, int $perPage = 10): LengthAwarePaginator
    {
        return $query->orderBy('wake_up_achievements', 'desc')
            ->paginate($perPage);
    }

    /**
     * ユーザーIDでユーザーを取得
     */
    public function findById(int $userId): ?User
    {
        return User::find($userId);
    }

    /**
     * 達成回数をインクリメント
     */
    public function incrementAchievements(int $userId): void
    {
        User::where('id', $userId)->increment('wake_up_achievements');
    }
}
