<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\WakeUpRecord;
use App\Services\WakeUpRecordService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WakeUpRecordServiceTest extends TestCase
{
    use RefreshDatabase;

    private WakeUpRecordService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(WakeUpRecordService::class);
    }

    public function test_record_wake_up_success_with_achievement(): void
    {
        $user = User::factory()->create([
            'target_wake_up_time' => '06:00:00',
            'wake_up_achievements' => 0,
        ]);

        Carbon::setTestNow('2024-01-01 05:30:00');
        $record = $this->service->recordWakeUp($user->id, $user->target_wake_up_time);

        $this->assertTrue($record->is_achieved);
        $this->assertEquals(1, $user->fresh()->wake_up_achievements);
    }

    public function test_record_wake_up_success_without_achievement(): void
    {
        $user = User::factory()->create([
            'target_wake_up_time' => '06:00:00',
            'wake_up_achievements' => 0,
        ]);

        Carbon::setTestNow('2024-01-01 07:00:00');
        $record = $this->service->recordWakeUp($user->id, $user->target_wake_up_time);

        $this->assertFalse($record->is_achieved);
        $this->assertEquals(0, $user->fresh()->wake_up_achievements);
    }

    public function test_record_wake_up_duplicate_throws_exception(): void
    {
        $user = User::factory()->create();

        $this->service->recordWakeUp($user->id, null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('本日の起床記録はすでに登録されています。');
        $this->service->recordWakeUp($user->id, null);
    }

    public function test_get_dashboard_data(): void
    {
        $user = User::factory()->create();

        WakeUpRecord::factory()->create([
            'user_id' => $user->id,
            'recorded_at' => Carbon::today(),
        ]);

        WakeUpRecord::factory()->count(5)->create([
            'user_id' => $user->id,
            'recorded_at' => Carbon::now()->subDays(3),
        ]);

        $data = $this->service->getDashboardData($user->id);

        $this->assertNotNull($data['todayRecord']);
        $this->assertCount(6, $data['recentRecords']);
    }
}
