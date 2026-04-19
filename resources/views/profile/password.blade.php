@extends('layouts.app', ['title' => 'Ganti Password', 'pageTitle' => 'Ganti Password'])

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autofocus>
                        @error('current_password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group mb-0">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key mr-1"></i>Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
