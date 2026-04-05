<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class FollowRepository
{
    /**
     * フォロー追加（重複時はスキップ）
     */
    public function follow(int $followerId, int $followedId): void
    {
        $follower = User::find($followerId);
        if ($follower && $followerId !== $followedId) {
            $follower->following()->syncWithoutDetaching([$followedId]);
        }
    }

    /**
     * フォロー解除
     */
    public function unfollow(int $followerId, int $followedId): void
    {
        $follower = User::find($followerId);
        if ($follower) {
            $follower->following()->detach($followedId);
        }
    }

    /**
     * フォロー中のユーザーID一覧を取得
     */
    public function getFollowingIds(int $userId): Collection
    {
        return User::find($userId)->following()->pluck('followed_id');
    }

    /**
     * 指定ユーザーが特定ユーザーをフォローしているか確認
     */
    public function isFollowing(int $followerId, int $followedId): bool
    {
        return User::find($followerId)
            ->following()
            ->where('followed_id', $followedId)
            ->exists();
    }
}
