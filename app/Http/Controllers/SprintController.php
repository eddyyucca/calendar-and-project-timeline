<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SprintController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeProjectWrite($request, $project);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(Sprint::STATUSES)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'goal' => ['nullable', 'string'],
        ]);

        $project->sprints()->create($data);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Sprint berhasil dibuat.');
    }

    private function authorizeProjectWrite(Request $request, Project $project): void
    {
        abort_unless($request->user()->isAdmin() || $project->owner_id === $request->user()->id, 403);
    }
}
