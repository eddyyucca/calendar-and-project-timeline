@extends('layouts.app', ['title' => 'Detail Aktivitas', 'pageTitle' => 'Detail Aktivitas'])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0 d-flex align-items-center justify-content-between">
                <h3 class="card-title font-weight-bold mb-0">{{ $activity->title }}</h3>
                <div>
                    <a href="{{ route('activities.edit', $activity) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    @if (auth()->user()->isAdmin())
                        <div class="col-md-3 mb-2">
                            <span class="text-muted d-block">Karyawan</span>
                            <strong>{{ $activity->user->name }}</strong>
                        </div>
                    @endif
                    <div class="col-md-3 mb-2">
                        <span class="text-muted d-block">Tanggal</span>
                        <strong>{{ $activity->activity_date->format('d/m/Y') }}</strong>
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="text-muted d-block">Kategori</span>
                        <span class="badge badge-info">{{ $activity->category }}</span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="text-muted d-block">Prioritas</span>
                        <span class="badge badge-warning">{{ $activity->priority }}</span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="text-muted d-block">Status</span>
                        <span class="badge badge-light border">{{ $activity->status }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <strong>Progress</strong>
                        <strong>{{ $activity->progress }}%</strong>
                    </div>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-{{ $activity->progress_color }}" style="width: {{ $activity->progress }}%"></div>
                    </div>
                </div>

                <h5 class="font-weight-bold">Deskripsi</h5>
                <p class="text-muted">{{ $activity->description ?: 'Tidak ada deskripsi.' }}</p>

                <h5 class="font-weight-bold">Kendala / Catatan</h5>
                <p class="text-muted">{{ $activity->blocker ?: 'Tidak ada kendala.' }}</p>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                <form method="POST" action="{{ route('activities.destroy', $activity) }}" data-confirm="Hapus aktivitas ini? Data komentar di aktivitas ini juga akan terhapus.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">Komentar Aktivitas</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('activities.comments.store', $activity) }}" class="mb-3">
                    @csrf
                    <div class="form-group">
                        <label for="progress">Persentase Progress</label>
                        <input type="number" id="progress" name="progress" value="{{ old('progress', $activity->progress) }}" min="0" max="100" class="form-control @error('progress') is-invalid @enderror" required>
                        @error('progress')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="comment">Komentar</label>
                        <textarea id="comment" name="comment" rows="3" class="form-control @error('comment') is-invalid @enderror" required>{{ old('comment') }}</textarea>
                        @error('comment')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="far fa-comment-dots mr-1"></i>Kirim Komentar
                    </button>
                </form>

                <div class="timeline timeline-inverse mb-0">
                    @forelse ($activity->comments as $comment)
                        <div>
                            <i class="fas fa-comments bg-primary"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="far fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</span>
                                <h3 class="timeline-header">
                                    {{ $comment->user->name }}
                                    @if (! is_null($comment->progress))
                                        <span class="badge badge-primary ml-1">{{ $comment->progress }}%</span>
                                    @endif
                                </h3>
                                <div class="timeline-body">{{ $comment->comment }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">Belum ada komentar.</div>
                    @endforelse
                    @if ($activity->comments->isNotEmpty())
                        <div><i class="far fa-clock bg-gray"></i></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
