<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

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
     * 同じ目標時間のユーザーでフィルタ
     */
    public function filterBySameTargetTime(Builder $query, string $targetTime): Builder
    {
        return $query->where('target_wake_up_time', $targetTime);
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
