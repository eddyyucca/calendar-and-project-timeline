<?php

namespace Tests\Feature;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_open_calendar_but_cannot_create_event(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $this->actingAs($employee)
            ->get(route('calendar.index'))
            ->assertOk();

        $this->actingAs($employee)
            ->post(route('calendar.store'), [
                'title' => 'Meeting pribadi',
                'type' => 'meeting',
                'event_date' => '2026-04-20',
            ])
            ->assertForbidden();
    }

    public function test_superadmin_can_create_meeting_event(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('calendar.store'), [
            'title' => 'Meeting evaluasi HRGA',
            'type' => 'meeting',
            'event_date' => '2026-04-20',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'description' => 'Bahas progres aktivitas mingguan.',
        ])->assertRedirect(route('calendar.index'));

        $this->assertDatabaseHas('calendar_events', [
            'title' => 'Meeting evaluasi HRGA',
            'type' => 'meeting',
        ]);
    }

    public function test_holiday_seeder_creates_2026_national_holidays(): void
    {
        $this->seed();

        $this->assertDatabaseHas('calendar_events', [
            'title' => 'Proklamasi Kemerdekaan RI',
            'type' => 'holiday',
        ]);

        $this->assertGreaterThanOrEqual(25, CalendarEvent::query()->where('is_national_holiday', true)->count());
    }
}
