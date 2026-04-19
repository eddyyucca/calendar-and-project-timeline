@php
    $prefix = $event ? '_'.$event->id : '';
@endphp

<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="title{{ $prefix }}">Judul</label>
            <input type="text" id="title{{ $prefix }}" name="title" value="{{ old('title', $event?->title) }}" class="form-control @error('title') is-invalid @enderror" required>
            @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="type{{ $prefix }}">Jenis</label>
            <select id="type{{ $prefix }}" name="type" class="form-control @error('type') is-invalid @enderror" required>
                <option value="meeting" @selected(old('type', $event?->type ?? 'meeting') === 'meeting')>Meeting Penting</option>
                <option value="reminder" @selected(old('type', $event?->type) === 'reminder')>Pengingat</option>
                <option value="holiday" @selected(old('type', $event?->type) === 'holiday')>Hari Nasional</option>
                <option value="leave" @selected(old('type', $event?->type) === 'leave')>Cuti Bersama</option>
            </select>
            @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="event_date{{ $prefix }}">Tanggal</label>
            <input type="date" id="event_date{{ $prefix }}" name="event_date" value="{{ old('event_date', $event?->event_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="form-control @error('event_date') is-invalid @enderror" required>
            @error('event_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="start_time{{ $prefix }}">Mulai</label>
            <input type="time" id="start_time{{ $prefix }}" name="start_time" value="{{ old('start_time', $event?->start_time ? substr($event->start_time, 0, 5) : '') }}" class="form-control @error('start_time') is-invalid @enderror">
            @error('start_time')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="end_time{{ $prefix }}">Selesai</label>
            <input type="time" id="end_time{{ $prefix }}" name="end_time" value="{{ old('end_time', $event?->end_time ? substr($event->end_time, 0, 5) : '') }}" class="form-control @error('end_time') is-invalid @enderror">
            @error('end_time')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
</div>

<div class="form-group mb-0">
    <label for="description{{ $prefix }}">Catatan</label>
    <textarea id="description{{ $prefix }}" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $event?->description) }}</textarea>
    @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>
