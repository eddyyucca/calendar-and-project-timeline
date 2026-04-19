<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_create_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Karyawan HRGA',
            'email' => 'karyawan@example.com',
            'role' => 'employee',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Karyawan HRGA',
            'email' => 'karyawan@example.com',
            'role' => 'employee',
        ]);
    }

    public function test_employee_cannot_open_user_management(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $this->actingAs($employee)
            ->get(route('users.index'))
            ->assertForbidden();
    }

    public function test_superadmin_can_reset_user_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = User::factory()->create(['role' => 'employee']);

        $this->actingAs($admin)->put(route('users.reset-password', $employee), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertRedirect(route('users.index'));

        $employee->refresh();

        $this->assertTrue(Hash::check('newpassword123', $employee->password));
    }

    public function test_user_can_change_own_password(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'oldpassword123',
            'password' => 'mynewpassword123',
            'password_confirmation' => 'mynewpassword123',
        ])->assertRedirect(route('password.edit'));

        $user->refresh();

        $this->assertTrue(Hash::check('mynewpassword123', $user->password));
    }
}
