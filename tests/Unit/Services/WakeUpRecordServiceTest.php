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

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
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

    public function test_record_wake_up_at_target_time_is_achievement(): void
    {
        $user = User::factory()->create([
            'target_wake_up_time' => '06:00:00',
            'wake_up_achievements' => 0,
        ]);

        Carbon::setTestNow(Carbon::parse('2024-01-01 06:00:00', 'Asia/Tokyo'));
        $record = $this->service->recordWakeUp($user->id, $user->target_wake_up_time);

        $this->assertTrue($record->is_achieved);
        $this->assertEquals(1, $user->fresh()->wake_up_achievements);
    }

    public function test_record_wake_up_without_target_is_not_achievement(): void
    {
        $user = User::factory()->create([
            'target_wake_up_time' => null,
            'wake_up_achievements' => 0,
        ]);

        Carbon::setTestNow(Carbon::parse('2024-01-01 05:30:00', 'Asia/Tokyo'));
        $record = $this->service->recordWakeUp($user->id, null);

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

    public function test_get_dashboard_data_uses_tokyo_date_boundary(): void
    {
        Carbon::setTestNowAndTimezone('2024-01-02 00:30:00', 'Asia/Tokyo');

        $user = User::factory()->create();
        $todayRecord = WakeUpRecord::factory()->create([
            'user_id' => $user->id,
            'recorded_at' => Carbon::parse('2024-01-02 00:15:00', 'Asia/Tokyo'),
        ]);
        WakeUpRecord::factory()->create([
            'user_id' => $user->id,
            'recorded_at' => Carbon::parse('2024-01-01 23:50:00', 'Asia/Tokyo'),
        ]);

        $data = $this->service->getDashboardData($user->id);

        $this->assertTrue($todayRecord->is($data['todayRecord']));
    }
}
