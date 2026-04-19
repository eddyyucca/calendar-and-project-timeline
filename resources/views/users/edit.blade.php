@extends('layouts.app', ['title' => 'Edit User', 'pageTitle' => 'Edit User'])

@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group mb-0">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="employee" @selected(old('role', $user->role) === 'employee')>Karyawan</option>
                            <option value="admin" @selected(old('role', $user->role) === 'admin')>Superadmin</option>
                        </select>
                        @error('role')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
