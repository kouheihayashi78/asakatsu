<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\FollowRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private FollowRepository $followRepository
    ) {}

    /**
     * ユーザー一覧をフィルタして取得
     */
    public function getUserList(
        int $currentUserId,
        string $filter = 'all',
        ?string $currentUserTargetTime = null
    ): LengthAwarePaginator {
        // 指定ユーザー以外のクエリ取得
        $query = $this->userRepository->queryExcludingUser($currentUserId);

        // フィルタリング
        match ($filter) {
            'same_target' => $currentUserTargetTime
                ? $this->userRepository->filterBySameTargetTime($query, $currentUserTargetTime)
                : $query,
            'following' => $this->userRepository->filterByFollowing($query, $currentUserId),
            default => $query,
        };

        return $this->userRepository->paginateByAchievements($query, 10);
    }

    /**
     * ユーザーデータを整形（フロントエンド用）
     */
    public function formatUsersForFrontend(LengthAwarePaginator $users, array $followingIds): LengthAwarePaginator
    {
        $users->getCollection()->transform(function ($user) use ($followingIds) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'age' => $user->age,
                'target_wake_up_time' => $user->target_wake_up_time
                    ? substr($user->target_wake_up_time, 0, 5)
                    : null,
                'wake_up_achievements' => $user->wake_up_achievements,
                'introduction' => $user->introduction,
                'is_following' => in_array($user->id, $followingIds),
                'today_record' => $user->wakeUpRecords->first(),
            ];
        });

        return $users;
    }
}
