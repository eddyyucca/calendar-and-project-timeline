<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_project_and_backlog_item(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'Implementasi HRGA Agile',
            'status' => 'active',
            'start_date' => '2026-04-20',
            'target_date' => '2026-05-20',
            'goal' => 'Membuat alur kerja project yang terukur.',
        ])->assertRedirect();

        $project = Project::first();

        $this->actingAs($user)->post(route('projects.tasks.store', $project), [
            'title' => 'Sebagai admin, saya bisa melihat board project',
            'type' => 'story',
            'priority' => 'high',
            'status' => 'todo',
            'story_points' => 5,
            'assignee_id' => $user->id,
            'description' => 'User story untuk board agile.',
            'acceptance_criteria' => 'Board menampilkan kolom agile.',
        ])->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('project_tasks', [
            'project_id' => $project->id,
            'title' => 'Sebagai admin, saya bisa melihat board project',
            'status' => 'todo',
            'story_points' => 5,
        ]);
    }

    public function test_assignee_can_update_task_status(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();

        $project = Project::create([
            'owner_id' => $owner->id,
            'name' => 'Project Test',
            'status' => 'active',
        ]);

        $task = ProjectTask::create([
            'project_id' => $project->id,
            'assignee_id' => $assignee->id,
            'reporter_id' => $owner->id,
            'title' => 'Review task',
            'type' => 'task',
            'priority' => 'medium',
            'status' => 'review',
            'story_points' => 3,
        ]);

        $this->actingAs($assignee)->patch(route('project-tasks.status', $task), [
            'status' => 'done',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('project_tasks', [
            'id' => $task->id,
            'status' => 'done',
        ]);
    }
}
