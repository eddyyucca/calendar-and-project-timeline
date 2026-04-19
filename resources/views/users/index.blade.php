@extends('layouts.app', ['title' => 'Manajemen User', 'pageTitle' => 'Manajemen User'])

@section('content')
<div class="card">
    <div class="card-header border-0 d-flex align-items-center justify-content-between">
        <h3 class="card-title font-weight-bold">Data User</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus mr-1"></i>Tambah User
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aktivitas</th>
                        <th>Rata-rata Progress</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php
                            $avg = (int) round($user->activities_avg_progress ?? 0);
                        @endphp
                        <tr>
                            <td class="font-weight-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->isAdmin() ? 'badge-primary' : 'badge-secondary' }}">
                                    {{ $user->isAdmin() ? 'Superadmin' : 'Karyawan' }}
                                </span>
                            </td>
                            <td>{{ $user->activities_count }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: {{ $avg }}%"></div>
                                </div>
                                <span class="small text-muted">{{ $avg }}%</span>
                            </td>
                            <td class="text-right">
                                <span class="table-action-group">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit user">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-warning" title="Reset password" data-toggle="modal" data-target="#resetPassword{{ $user->id }}">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    @endif
</div>

@foreach ($users as $user)
    <div class="modal fade" id="resetPassword{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('users.reset-password', $user) }}" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle mr-1"></i>Password baru akan berlaku untuk <strong>{{ $user->name }}</strong>.
                    </div>
                    <div class="form-group">
                        <label for="password_{{ $user->id }}">Password Baru</label>
                        <input type="password" id="password_{{ $user->id }}" name="password" class="form-control" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="password_confirmation_{{ $user->id }}">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation_{{ $user->id }}" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key mr-1"></i>Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection
