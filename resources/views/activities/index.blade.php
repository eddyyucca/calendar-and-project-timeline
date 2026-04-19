@extends('layouts.app', ['title' => 'Daily Activity', 'pageTitle' => 'Daily Activity'])

@section('content')
<div class="card">
    <div class="card-header border-0 d-flex align-items-center flex-wrap gap-2">
        <h3 class="card-title mr-auto">Daftar Aktivitas</h3>
        <a href="{{ route('activities.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus mr-1"></i>Tambah Aktivitas
        </a>
    </div>
    <div class="card-body border-top pt-3 pb-2">
        <form method="GET" action="{{ route('activities.index') }}" class="row align-items-end">
            <div class="{{ auth()->user()->isAdmin() ? 'col-md-4' : 'col-md-5' }} mb-2">
                <label for="search" class="small font-weight-bold mb-1">Cari Aktivitas</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Judul, deskripsi, kategori">
            </div>
            @if (auth()->user()->isAdmin())
                <div class="col-md-3 mb-2">
                    <label for="employee_id" class="small font-weight-bold mb-1">Karyawan</label>
                    <select id="employee_id" name="employee_id" class="form-control form-control-sm">
                        <option value="">Semua karyawan</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @selected((int) request('employee_id') === $employee->id)>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="{{ auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4' }} mb-2">
                <label for="status" class="small font-weight-bold mb-1">Status</label>
                <select id="status" name="status" class="form-control form-control-sm">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="{{ auth()->user()->isAdmin() ? 'col-md-2' : 'col-md-3' }} mb-2">
                <label class="invisible small mb-1">.</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="fas fa-search mr-1"></i>Filter
                    </button>
                    <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="fas fa-sync-alt mr-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Aktivitas</th>
                        @if (auth()->user()->isAdmin())
                            <th>Karyawan</th>
                        @endif
                        <th>Kategori</th>
                        <th>Status</th>
                        <th style="min-width: 170px;">Progress</th>
                        <th>Komentar</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr>
                            <td>{{ $activity->activity_date->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('activities.show', $activity) }}" class="font-weight-bold">{{ $activity->title }}</a>
                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit($activity->description, 70) }}</div>
                            </td>
                            @if (auth()->user()->isAdmin())
                                <td>{{ $activity->user->name }}</td>
                            @endif
                            <td><span class="badge badge-info">{{ $activity->category }}</span></td>
                            <td><span class="badge badge-light border">{{ $activity->status }}</span></td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $activity->progress_color }}" style="width: {{ $activity->progress }}%"></div>
                                </div>
                                <span class="small text-muted">{{ $activity->progress }}%</span>
                            </td>
                            <td><i class="far fa-comments text-primary mr-1"></i>{{ $activity->comments_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('activities.edit', $activity) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}" class="text-center text-muted py-4">Belum ada aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($activities->hasPages())
        <div class="card-footer">
            {{ $activities->links() }}
        </div>
    @endif
</div>
@endsection
