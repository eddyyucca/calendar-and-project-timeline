@php
    $isEdit = $activity->exists;
@endphp

@extends('layouts.app', ['title' => $isEdit ? 'Edit Aktivitas' : 'Tambah Aktivitas', 'pageTitle' => $isEdit ? 'Edit Aktivitas' : 'Tambah Aktivitas'])

@section('content')
<form method="POST" action="{{ $isEdit ? route('activities.update', $activity) : route('activities.store') }}">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bold">Informasi Aktivitas</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Judul Aktivitas</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $activity->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi Pekerjaan</label>
                        <textarea id="description" name="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description', $activity->description) }}</textarea>
                        @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="blocker">Kendala / Catatan</label>
                        <textarea id="blocker" name="blocker" rows="3" class="form-control @error('blocker') is-invalid @enderror">{{ old('blocker', $activity->blocker) }}</textarea>
                        @error('blocker')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bold">Kontrol Progress</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="activity_date">Tanggal</label>
                        <input type="date" id="activity_date" name="activity_date" value="{{ old('activity_date', optional($activity->activity_date)->format('Y-m-d')) }}" class="form-control @error('activity_date') is-invalid @enderror" required>
                        @error('activity_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category" class="form-control @error('category') is-invalid @enderror" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(old('category', $activity->category) === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="priority">Prioritas</label>
                        <select id="priority" name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority }}" @selected(old('priority', $activity->priority) === $priority)>{{ $priority }}</option>
                            @endforeach
                        </select>
                        @error('priority')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', $activity->status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="progress">Persentase Pekerjaan</label>
                        <input type="number" id="progress" name="progress" value="{{ old('progress', $activity->progress) }}" min="0" max="100" class="form-control @error('progress') is-invalid @enderror" required>
                        @error('progress')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ $isEdit ? route('activities.show', $activity) : route('activities.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
