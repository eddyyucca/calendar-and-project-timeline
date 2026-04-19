<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectTaskController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeProjectWrite($request, $project);

        $data = $request->validate([
            'sprint_id' => ['nullable', 'exists:sprints,id'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(ProjectTask::TYPES)],
            'priority' => ['required', Rule::in(ProjectTask::PRIORITIES)],
            'status' => ['required', Rule::in(ProjectTask::STATUSES)],
            'story_points' => ['required', 'integer', 'min:1', 'max:100'],
            'description' => ['nullable', 'string'],
            'acceptance_criteria' => ['nullable', 'string'],
        ]);

        if (! empty($data['sprint_id'])) {
            abort_unless($project->sprints()->whereKey($data['sprint_id'])->exists(), 422);
        }

        $project->tasks()->create($data + [
            'reporter_id' => $request->user()->id,
            'completed_at' => $data['status'] === 'done' ? now() : null,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Backlog item berhasil dibuat.');
    }

    public function updateStatus(Request $request, ProjectTask $projectTask): RedirectResponse
    {
        $this->authorizeTaskAccess($request, $projectTask);

        $data = $request->validate([
            'status' => ['required', Rule::in(ProjectTask::STATUSES)],
        ]);

        $projectTask->update([
            'status' => $data['status'],
            'completed_at' => $data['status'] === 'done' ? now() : null,
        ]);

        return back()->with('success', 'Status task berhasil diperbarui.');
    }

    private function authorizeProjectWrite(Request $request, Project $project): void
    {
        abort_unless($request->user()->isAdmin() || $project->owner_id === $request->user()->id, 403);
    }

    private function authorizeTaskAccess(Request $request, ProjectTask $projectTask): void
    {
        abort_unless(
            $request->user()->isAdmin()
            || $projectTask->project->owner_id === $request->user()->id
            || $projectTask->assignee_id === $request->user()->id,
            403
        );
    }
}
