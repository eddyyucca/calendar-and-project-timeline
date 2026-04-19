@extends('layouts.app', ['title' => 'Project Agile', 'pageTitle' => 'Project Agile'])

@section('content')
<div class="card">
    <div class="card-header border-0 d-flex align-items-center justify-content-between">
        <h3 class="card-title font-weight-bold">Daftar Project</h3>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i>Project Baru
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Target</th>
                        <th style="min-width: 180px;">Progress</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        @php
                            $progress = $project->tasks_count > 0 ? (int) round(($project->done_tasks_count / $project->tasks_count) * 100) : 0;
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('projects.show', $project) }}" class="font-weight-bold">{{ $project->name }}</a>
                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit($project->goal, 90) }}</div>
                            </td>
                            <td>{{ $project->owner->name }}</td>
                            <td><span class="badge badge-light border text-capitalize">{{ str_replace('_', ' ', $project->status) }}</span></td>
                            <td>{{ $project->target_date?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: {{ $progress }}%"></div>
                                </div>
                                <span class="small text-muted">{{ $progress }}% - {{ $project->done_tasks_count }}/{{ $project->tasks_count }} done</span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-columns mr-1"></i>Board
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada project.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($projects->hasPages())
        <div class="card-footer">{{ $projects->links() }}</div>
    @endif
</div>
@endsection
