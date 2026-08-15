<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    public function test_query_excluding_user(): void
    {
        $currentUser = User::factory()->create();
        $otherUser = User::factory()->create();

        $query = $this->repository->queryExcludingUser($currentUser->id);
        $users = $query->get();

        $this->assertCount(1, $users);
        $this->assertEquals($otherUser->id, $users->first()->id);
    }

    public function test_filter_by_same_target_hour(): void
    {
        $atStart = User::factory()->create(['target_wake_up_time' => '06:00:00']);
        $withinHour = User::factory()->create(['target_wake_up_time' => '06:59:59']);
        User::factory()->create(['target_wake_up_time' => '05:59:59']);
        User::factory()->create(['target_wake_up_time' => '07:00:00']);
        User::factory()->create(['target_wake_up_time' => null]);

        $query = User::query();
        $users = $this->repository
            ->filterBySameTargetHour($query, '06:30:00')
            ->get();

        $this->assertCount(2, $users);
        $this->assertTrue($users->contains($atStart));
        $this->assertTrue($users->contains($withinHour));
    }

    public function test_increment_achievements(): void
    {
        $user = User::factory()->create(['wake_up_achievements' => 5]);

        $this->repository->incrementAchievements($user->id);

        $this->assertEquals(6, $user->fresh()->wake_up_achievements);
    }

    public function test_find_by_id(): void
    {
        $user = User::factory()->create();

        $found = $this->repository->findById($user->id);

        $this->assertNotNull($found);
        $this->assertEquals($user->id, $found->id);
    }

    public function test_find_by_id_not_found(): void
    {
        $found = $this->repository->findById(999999);

        $this->assertNull($found);
    }
}
