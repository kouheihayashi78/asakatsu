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

    public function test_filter_by_same_target_time(): void
    {
        User::factory()->create(['target_wake_up_time' => '06:00:00']);
        User::factory()->create(['target_wake_up_time' => '07:00:00']);

        $query = User::query();
        $filteredQuery = $this->repository->filterBySameTargetTime($query, '06:00:00');

        $this->assertCount(1, $filteredQuery->get());
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
