<?php


namespace Tests\Unit\Models;

use App\Models\Schedule;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopModelTest extends TestCase
{
    use RefreshDatabase;

    private Shop $shop;
    private Model $schedule;

    public function setUp(): void
    {
        parent::setUp();
        $this->shop = app()->make(Shop::class);
        $this->schedule = Schedule::factory()->create();
        Shop::factory([
            'schedule_id' => $this->schedule->id
        ])
            ->count(5)
            ->create();
    }

    public function testScopeSchedulesWith()
    {
        $result = $this->shop->withSchedules(true)->get();
        $toArraySchedule = $this->schedule->toArray();
        foreach ($result->toArray() as $value) {
            $this->assertTrue(isset($value['schedule']));
            $this->assertEquals($value['schedule'], $toArraySchedule);
        }
    }

    public function testScopeSchedulesWithNot()
    {
        $result = $this->shop->withSchedules(false)->get();
        foreach ($result->toArray() as $value) {
            $this->assertFalse(isset($value['schedule']));
        }
    }
}
