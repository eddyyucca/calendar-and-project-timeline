<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Sprint;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->with('owner')
            ->withCount('tasks')
            ->withCount(['tasks as done_tasks_count' => fn ($query) => $query->where('status', 'done')])
            ->when(! $request->user()->isAdmin(), function ($query) use ($request): void {
                $query->where('owner_id', $request->user()->id)
                    ->orWhereHas('tasks', fn ($taskQuery) => $taskQuery->where('assignee_id', $request->user()->id));
            })
            ->latest()
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(Project::STATUSES)],
            'start_date' => ['nullable', 'date'],
            'target_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'goal' => ['nullable', 'string'],
        ]);

        $project = Project::create($data + [
            'owner_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project berhasil dibuat.');
    }

    public function show(Request $request, Project $project): View
    {
        $this->authorizeProjectAccess($request, $project);

        $project->load([
            'owner',
            'sprints.tasks',
            'tasks.assignee',
            'tasks.sprint',
        ]);

        $board = collect(ProjectTask::STATUSES)->mapWithKeys(fn (string $status) => [
            $status => $project->tasks->where('status', $status)->values(),
        ]);

        return view('projects.show', [
            'project' => $project,
            'board' => $board,
            'users' => User::query()->orderBy('name')->get(['id', 'name', 'email']),
            'statuses' => ProjectTask::STATUSES,
            'types' => ProjectTask::TYPES,
            'priorities' => ProjectTask::PRIORITIES,
            'sprintStatuses' => Sprint::STATUSES,
        ]);
    }

    private function authorizeProjectAccess(Request $request, Project $project): void
    {
        abort_unless(
            $request->user()->isAdmin()
            || $project->owner_id === $request->user()->id
            || $project->tasks()->where('assignee_id', $request->user()->id)->exists(),
            403
        );
    }
}
