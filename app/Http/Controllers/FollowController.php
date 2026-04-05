<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FollowService;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function __construct(
        private FollowService $followService
    ) {}

    public function store(Request $request, User $user)
    {
        $this->followService->follow($request->user()->id, $user->id);
        return back();
    }

    public function destroy(Request $request, User $user)
    {
        $this->followService->unfollow($request->user()->id, $user->id);
        return back();
    }
}
