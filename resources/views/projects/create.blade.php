@extends('layouts.app', ['title' => 'Project Baru', 'pageTitle' => 'Project Baru'])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h3 class="font-weight-bold mb-2">Buat Project Agile</h3>
                <p class="text-muted mb-0">
                    Project adalah wadah kerja untuk mengelola goal, sprint, dan backlog. Setelah project dibuat, Anda bisa menambahkan sprint dan memecah pekerjaan menjadi item kecil yang dapat dipantau di Kanban board.
                </p>
            </div>
        </div>

        <div class="card">
            <form method="POST" action="{{ route('projects.store') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nama Project</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="goal">Product Goal</label>
                        <textarea id="goal" name="goal" rows="4" class="form-control @error('goal') is-invalid @enderror" placeholder="Tujuan bisnis/project yang ingin dicapai">{{ old('goal') }}</textarea>
                        @error('goal')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="planning">Planning</option>
                                    <option value="active">Active</option>
                                    <option value="on_hold">On Hold</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Mulai</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="form-control @error('start_date') is-invalid @enderror">
                                @error('start_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="target_date">Target</label>
                                <input type="date" id="target_date" name="target_date" value="{{ old('target_date') }}" class="form-control @error('target_date') is-invalid @enderror">
                                @error('target_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Simpan Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
