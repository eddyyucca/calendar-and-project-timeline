@extends('layouts.app', ['title' => $project->name, 'pageTitle' => $project->name])

@section('content')
<style>
    .agile-board {
        display: grid;
        grid-template-columns: repeat(5, minmax(220px, 1fr));
        gap: .85rem;
        overflow-x: auto;
        padding-bottom: .5rem;
    }

    .agile-column {
        min-width: 220px;
        background: #f8fbff;
        border: 1px solid #e5edf7;
        border-radius: .65rem;
        padding: .75rem;
    }

    .agile-column-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .75rem;
        font-weight: 800;
        color: #334155;
    }

    .agile-task {
        border: 1px solid #e1eaf5;
        border-radius: .6rem;
        background: #fff;
        padding: .75rem;
        margin-bottom: .7rem;
        box-shadow: 0 .15rem .5rem rgba(15, 95, 184, .05);
    }

    .agile-task-title {
        display: block;
        color: #1f2937;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: .45rem;
    }

    @media (max-width: 991.98px) {
        .agile-board {
            grid-template-columns: repeat(5, 240px);
        }
    }
</style>

@php
    $doneCount = $project->tasks->where('status', 'done')->count();
    $progress = $project->tasks->count() > 0 ? (int) round(($doneCount / $project->tasks->count()) * 100) : 0;
    $statusLabels = [
        'backlog' => 'Backlog',
        'todo' => 'To Do',
        'in_progress' => 'In Progress',
        'review' => 'Review',
        'done' => 'Done',
    ];
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0 d-flex align-items-center justify-content-between">
                <h3 class="card-title font-weight-bold">Agile Board</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal">
                    <i class="fas fa-plus mr-1"></i>Backlog Item
                </button>
            </div>
            <div class="card-body">
                <div class="agile-board">
                    @foreach ($board as $status => $tasks)
                        <div class="agile-column">
                            <div class="agile-column-title">
                                <span>{{ $statusLabels[$status] }}</span>
                                <span class="badge badge-primary">{{ $tasks->count() }}</span>
                            </div>
                            @forelse ($tasks as $task)
                                <div class="agile-task">
                                    <span class="agile-task-title">{{ $task->title }}</span>
                                    <div class="mb-2">
                                        <span class="badge badge-light border text-capitalize">{{ $task->type }}</span>
                                        <span class="badge badge-warning text-capitalize">{{ $task->priority }}</span>
                                        <span class="badge badge-info">{{ $task->story_points }} SP</span>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        {{ $task->assignee?->name ?? 'Unassigned' }}
                                        @if ($task->sprint)
                                            - {{ $task->sprint->name }}
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('project-tasks.status', $task) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            @foreach ($statuses as $nextStatus)
                                                <option value="{{ $nextStatus }}" @selected($task->status === $nextStatus)>{{ $statusLabels[$nextStatus] }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            @empty
                                <div class="text-muted small">Belum ada item.</div>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">Project Summary</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $project->goal ?: 'Belum ada product goal.' }}</p>
                <div class="d-flex justify-content-between">
                    <strong>Progress</strong>
                    <strong>{{ $progress }}%</strong>
                </div>
                <div class="progress my-2">
                    <div class="progress-bar bg-primary" style="width: {{ $progress }}%"></div>
                </div>
                <div class="small text-muted">{{ $doneCount }} dari {{ $project->tasks->count() }} item selesai</div>
                <hr>
                <div class="small text-muted">Owner</div>
                <strong>{{ $project->owner->name }}</strong>
                <div class="small text-muted mt-2">Target</div>
                <strong>{{ $project->target_date?->format('d/m/Y') ?? '-' }}</strong>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0 d-flex align-items-center justify-content-between">
                <h3 class="card-title font-weight-bold">Sprint</h3>
                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#sprintModal">
                    <i class="fas fa-plus mr-1"></i>Tambah
                </button>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse ($project->sprints->sortByDesc('start_date') as $sprint)
                        <li class="list-group-item">
                            <strong>{{ $sprint->name }}</strong>
                            <div class="small text-muted">
                                {{ $sprint->start_date->format('d/m/Y') }} - {{ $sprint->end_date->format('d/m/Y') }}
                                - {{ ucfirst($sprint->status) }}
                            </div>
                            @if ($sprint->goal)
                                <div class="small mt-1">{{ $sprint->goal }}</div>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Belum ada sprint.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" action="{{ route('projects.tasks.store', $project) }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Backlog Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="title">Judul</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type">Tipe</label>
                            <select id="type" name="type" class="form-control">
                                @foreach ($types as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="priority">Prioritas</label>
                            <select id="priority" name="priority" class="form-control">
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="story_points">Story Points</label>
                            <input type="number" id="story_points" name="story_points" value="1" min="1" max="100" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}">{{ $statusLabels[$status] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="assignee_id">Assignee</label>
                            <select id="assignee_id" name="assignee_id" class="form-control">
                                <option value="">Unassigned</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sprint_id">Sprint</label>
                            <select id="sprint_id" name="sprint_id" class="form-control">
                                <option value="">Product Backlog</option>
                                @foreach ($project->sprints as $sprint)
                                    <option value="{{ $sprint->id }}">{{ $sprint->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" class="form-control"></textarea>
                </div>
                <div class="form-group mb-0">
                    <label for="acceptance_criteria">Acceptance Criteria</label>
                    <textarea id="acceptance_criteria" name="acceptance_criteria" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Item</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="sprintModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('projects.sprints.store', $project) }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Sprint</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="sprint_name">Nama Sprint</label>
                    <input type="text" id="sprint_name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="sprint_status">Status</label>
                    <select id="sprint_status" name="status" class="form-control">
                        @foreach ($sprintStatuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sprint_start_date">Mulai</label>
                            <input type="date" id="sprint_start_date" name="start_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sprint_end_date">Selesai</label>
                            <input type="date" id="sprint_end_date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label for="sprint_goal">Sprint Goal</label>
                    <textarea id="sprint_goal" name="goal" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Sprint</button>
            </div>
        </form>
    </div>
</div>
@endsection
