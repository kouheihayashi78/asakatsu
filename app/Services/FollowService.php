<?php

namespace App\Services;

use App\Repositories\FollowRepository;

class FollowService
{
    public function __construct(
        private FollowRepository $followRepository
    ) {}

    /**
     * ユーザーをフォロー
     */
    public function follow(int $followerId, int $followedId): void
    {
        $this->followRepository->follow($followerId, $followedId);
    }

    /**
     * ユーザーのフォローを解除
     */
    public function unfollow(int $followerId, int $followedId): void
    {
        $this->followRepository->unfollow($followerId, $followedId);
    }

    /**
     * フォロー中のユーザーID一覧を取得
     */
    public function getFollowingIds(int $userId): array
    {
        return $this->followRepository->getFollowingIds($userId)->toArray();
    }
}
