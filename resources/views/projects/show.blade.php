@extends('layouts.app', ['title' => $project->name, 'pageTitle' => $project->name])

@section('content')
<style>
    .project-hero {
        border: 1px solid #e5edf7;
        border-radius: .75rem;
        background: #fff;
        padding: 1.1rem;
        box-shadow: 0 .2rem .7rem rgba(15, 95, 184, .06);
    }

    .project-hero-title {
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: .35rem;
    }

    .project-meta-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(150px, 1fr));
        gap: .75rem;
        margin-top: 1rem;
    }

    .project-meta-item {
        border: 1px solid #e5edf7;
        border-radius: .6rem;
        padding: .8rem;
        background: #f8fbff;
    }

    .project-meta-label {
        color: #64748b;
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .project-meta-value {
        display: block;
        color: #1f2937;
        font-size: 1.05rem;
        font-weight: 800;
        margin-top: .25rem;
    }

    .agile-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        justify-content: flex-end;
    }

    .agile-board {
        display: grid;
        grid-template-columns: repeat(5, minmax(250px, 1fr));
        gap: .9rem;
        overflow-x: auto;
        padding-bottom: .5rem;
    }

    .agile-column {
        min-width: 250px;
        background: #f8fbff;
        border: 1px solid #e5edf7;
        border-radius: .7rem;
        padding: .8rem;
    }

    .agile-column-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .75rem;
        color: #334155;
        font-weight: 800;
    }

    .agile-task {
        border: 1px solid #e1eaf5;
        border-radius: .65rem;
        background: #fff;
        padding: .8rem;
        margin-bottom: .75rem;
        box-shadow: 0 .15rem .5rem rgba(15, 95, 184, .05);
    }

    .agile-task-title {
        display: block;
        color: #1f2937;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: .5rem;
    }

    .agile-help-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .75rem;
    }

    .agile-help-item {
        border: 1px solid #e5edf7;
        border-radius: .6rem;
        padding: .85rem;
        background: #fff;
    }

    .agile-help-item strong {
        display: block;
        margin-bottom: .25rem;
    }

    @media (max-width: 991.98px) {
        .project-meta-grid,
        .agile-help-grid {
            grid-template-columns: 1fr 1fr;
        }

        .agile-board {
            grid-template-columns: repeat(5, 260px);
        }
    }

    @media (max-width: 575.98px) {
        .project-meta-grid,
        .agile-help-grid {
            grid-template-columns: 1fr;
        }

        .agile-actions {
            display: grid;
            justify-content: stretch;
        }
    }
</style>

@php
    $doneCount = $project->tasks->where('status', 'done')->count();
    $progress = $project->tasks->count() > 0 ? (int) round(($doneCount / $project->tasks->count()) * 100) : 0;
    $activeSprint = $project->sprints->where('status', 'active')->sortByDesc('start_date')->first();
    $statusLabels = [
        'backlog' => 'Backlog',
        'todo' => 'To Do',
        'in_progress' => 'In Progress',
        'review' => 'Review',
        'done' => 'Done',
    ];
@endphp

<div class="project-hero mb-3">
    <div class="d-flex flex-column flex-lg-row justify-content-between">
        <div class="pr-lg-4">
            <div class="project-hero-title">Workspace Agile Project</div>
            <p class="text-muted mb-2">
                Halaman ini dipakai untuk mengelola project dengan ritme agile: susun product goal, pecah pekerjaan menjadi backlog item, kelompokkan ke sprint, lalu pantau alurnya di board Kanban.
            </p>
            <p class="mb-0">{{ $project->goal ?: 'Belum ada product goal. Tambahkan goal agar tim punya arah yang sama.' }}</p>
        </div>
        <div class="agile-actions mt-3 mt-lg-0">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal">
                <i class="fas fa-plus mr-1"></i>Backlog Item
            </button>
            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#sprintModal">
                <i class="fas fa-running mr-1"></i>Sprint
            </button>
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i>Project
            </a>
        </div>
    </div>

    <div class="project-meta-grid">
        <div class="project-meta-item">
            <span class="project-meta-label">Progress</span>
            <span class="project-meta-value">{{ $progress }}%</span>
            <div class="progress mt-2">
                <div class="progress-bar bg-primary" style="width: {{ $progress }}%"></div>
            </div>
        </div>
        <div class="project-meta-item">
            <span class="project-meta-label">Backlog Done</span>
            <span class="project-meta-value">{{ $doneCount }}/{{ $project->tasks->count() }}</span>
        </div>
        <div class="project-meta-item">
            <span class="project-meta-label">Active Sprint</span>
            <span class="project-meta-value">{{ $activeSprint?->name ?? '-' }}</span>
        </div>
        <div class="project-meta-item">
            <span class="project-meta-label">Target</span>
            <span class="project-meta-value">{{ $project->target_date?->format('d/m/Y') ?? '-' }}</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <div>
            <h3 class="card-title font-weight-bold">Kanban Board</h3>
            <div class="text-muted small mt-1">Pindahkan status item untuk menggambarkan flow kerja dari backlog sampai selesai.</div>
        </div>
        <span class="badge badge-light border text-capitalize mt-2 mt-md-0">{{ str_replace('_', ' ', $project->status) }}</span>
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
                                <i class="far fa-user mr-1"></i>{{ $task->assignee?->name ?? 'Unassigned' }}
                                @if ($task->sprint)
                                    <br><i class="fas fa-running mr-1"></i>{{ $task->sprint->name }}
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

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">Sprint Plan</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse ($project->sprints->sortByDesc('start_date') as $sprint)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $sprint->name }}</strong>
                                    <div class="small text-muted">
                                        {{ $sprint->start_date->format('d/m/Y') }} - {{ $sprint->end_date->format('d/m/Y') }}
                                        - {{ ucfirst($sprint->status) }}
                                    </div>
                                    @if ($sprint->goal)
                                        <div class="small mt-1">{{ $sprint->goal }}</div>
                                    @endif
                                </div>
                                <span class="badge badge-light border align-self-start">{{ $sprint->tasks->count() }} item</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Belum ada sprint. Buat sprint untuk mengelompokkan backlog dalam periode kerja tertentu.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">Fungsi Utama</h3>
            </div>
            <div class="card-body">
                <div class="agile-help-grid">
                    <div class="agile-help-item">
                        <strong>Product Goal</strong>
                        <span class="text-muted small">Menjelaskan hasil akhir yang ingin dicapai project.</span>
                    </div>
                    <div class="agile-help-item">
                        <strong>Sprint</strong>
                        <span class="text-muted small">Periode kerja singkat untuk menyelesaikan item prioritas.</span>
                    </div>
                    <div class="agile-help-item">
                        <strong>Board</strong>
                        <span class="text-muted small">Memantau posisi setiap item dari backlog sampai done.</span>
                    </div>
                </div>
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
