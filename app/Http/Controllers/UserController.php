<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\FollowService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
        private FollowService $followService
    ) {}

    public function index(Request $request)
    {
        $currentUser = $request->user();
        $filter = $request->input('filter', 'all');

        // ユーザー一覧を取得
        $users = $this->userService->getUserList(
            $currentUser->id,
            $filter,
            $currentUser->target_wake_up_time
        );

        // フォロー中のID一覧を取得
        $followingIds = $this->followService->getFollowingIds($currentUser->id);

        // フロントエンド用にデータ整形
        $formattedUsers = $this->userService->formatUsersForFrontend($users, $followingIds);

        return Inertia::render('Users/Index', [
            'users' => $formattedUsers,
            'filters' => ['filter' => $filter],
            'currentUserTargetTime' => $currentUser->target_wake_up_time
                ? substr($currentUser->target_wake_up_time, 0, 5)
                : null
        ]);
    }
}
