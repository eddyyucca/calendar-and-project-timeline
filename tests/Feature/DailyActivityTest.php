<?php

namespace Tests\Feature;

use App\Models\DailyActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_user_can_create_activity_and_comment(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('activities.store'), [
            'title' => 'Follow up maintenance AC',
            'activity_date' => now()->format('Y-m-d'),
            'category' => 'Operasional',
            'priority' => 'Normal',
            'status' => 'Berjalan',
            'progress' => 45,
            'description' => 'Koordinasi dengan vendor untuk jadwal pekerjaan.',
            'blocker' => null,
        ]);

        $activity = DailyActivity::first();

        $response->assertRedirect(route('activities.show', $activity));
        $this->assertDatabaseHas('daily_activities', [
            'user_id' => $user->id,
            'title' => 'Follow up maintenance AC',
            'progress' => 45,
        ]);

        $this->actingAs($user)->post(route('activities.comments.store', $activity), [
            'comment' => 'Vendor sudah konfirmasi jadwal.',
            'progress' => 65,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('activity_comments', [
            'daily_activity_id' => $activity->id,
            'progress' => 65,
            'comment' => 'Vendor sudah konfirmasi jadwal.',
        ]);

        $this->assertDatabaseHas('daily_activities', [
            'id' => $activity->id,
            'progress' => 65,
        ]);
    }

    public function test_user_cannot_open_another_users_activity(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $activity = DailyActivity::create([
            'user_id' => $owner->id,
            'title' => 'Private activity',
            'activity_date' => now(),
            'category' => 'HRGA',
            'priority' => 'Tinggi',
            'status' => 'Berjalan',
            'progress' => 50,
        ]);

        $this->actingAs($other)
            ->get(route('activities.show', $activity))
            ->assertForbidden();
    }

    public function test_admin_can_open_all_employee_activities(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = User::factory()->create(['role' => 'employee']);

        $activity = DailyActivity::create([
            'user_id' => $employee->id,
            'title' => 'Employee activity',
            'activity_date' => now(),
            'category' => 'HRGA',
            'priority' => 'Normal',
            'status' => 'Berjalan',
            'progress' => 40,
        ]);

        $this->actingAs($admin)
            ->get(route('activities.show', $activity))
            ->assertOk()
            ->assertSee('Employee activity');
    }
}
