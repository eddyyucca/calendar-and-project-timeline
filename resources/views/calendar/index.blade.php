@extends('layouts.app', ['title' => 'Kalender Aktivitas', 'pageTitle' => 'Kalender Aktivitas'])

@section('content')
<style>
    .calendar-shell {
        display: block;
    }

    .calendar-card .card-body {
        padding: 1.1rem;
    }

    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        color: #5f6f82;
        font-size: .88rem;
    }

    .legend-dot {
        display: inline-block;
        width: .7rem;
        height: .7rem;
        border-radius: 999px;
        margin-right: .35rem;
    }

    .fc {
        --fc-border-color: #e5edf7;
        --fc-button-bg-color: #0f5fb8;
        --fc-button-border-color: #0f5fb8;
        --fc-button-hover-bg-color: #084887;
        --fc-button-hover-border-color: #084887;
        --fc-button-active-bg-color: #084887;
        --fc-button-active-border-color: #084887;
        font-size: .92rem;
    }

    .fc .fc-toolbar-title {
        color: #1f2937;
        font-size: 1.35rem;
        font-weight: 800;
    }

    .fc .fc-daygrid-day-number {
        color: #1f2937;
        font-weight: 700;
    }

    .fc .fc-event {
        border: 0;
        border-radius: .35rem;
        padding: .12rem .25rem;
    }

    .calendar-event-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: .2rem .55rem;
        color: #fff;
        font-size: .75rem;
        font-weight: 700;
    }

    @media (max-width: 991.98px) {
        .fc .fc-toolbar {
            display: grid;
            grid-template-columns: 1fr;
            gap: .65rem;
        }

        .fc .fc-toolbar-title {
            font-size: 1.15rem;
            text-align: center;
        }
    }
 </style>

<div class="calendar-shell">
    <div class="card calendar-card">
        <div class="card-header border-0">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h3 class="card-title font-weight-bold mb-1">Kalender Pengingat</h3>
                    <div class="calendar-legend">
                        <span><span class="legend-dot" style="background:#64748b"></span>Aktivitas</span>
                        <span><span class="legend-dot" style="background:#0f5fb8"></span>Meeting</span>
                        <span><span class="legend-dot" style="background:#16a34a"></span>Pengingat</span>
                        <span><span class="legend-dot" style="background:#dc2626"></span>Hari Nasional</span>
                        <span><span class="legend-dot" style="background:#f59e0b"></span>Cuti Bersama</span>
                    </div>
                </div>
                @if (auth()->user()->isAdmin())
                    <button type="button" class="btn btn-primary mt-3 mt-md-0" data-toggle="modal" data-target="#createEventModal">
                        <i class="fas fa-plus mr-1"></i>Tambah Event
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                <div class="mb-3 mb-lg-0">
                    <h3 class="card-title font-weight-bold">Daftar Event Kalender</h3>
                    <div class="text-muted small mt-1">Cek event, hari nasional, cuti bersama, dan meeting penting dari tabel ini.</div>
                </div>
                <span class="text-muted small">{{ $calendarEvents->total() }} event</span>
            </div>
        </div>
        <div class="card-body border-top">
            <form method="GET" action="{{ route('calendar.index') }}" class="row align-items-end">
                <div class="col-md-8 mb-2">
                    <label for="search">Cari Event</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Judul, catatan, meeting, holiday, reminder">
                </div>
                <div class="col-md-4 mb-2 text-md-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search mr-1"></i>Cari
                    </button>
                    <a href="{{ route('calendar.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt mr-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Tanggal</th>
                            <th style="width: 120px;">Jam</th>
                            <th>Event</th>
                            <th style="width: 160px;">Jenis</th>
                            <th>Catatan</th>
                            @if (auth()->user()->isAdmin())
                                <th class="text-right" style="width: 120px;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($calendarEvents as $event)
                            <tr>
                                <td>
                                    <strong>{{ $event->event_date->format('d/m/Y') }}</strong>
                                    <div class="small text-muted">{{ $event->event_date->translatedFormat('D') }}</div>
                                </td>
                                <td>
                                    @if ($event->start_time || $event->end_time)
                                        {{ $event->start_time ? substr($event->start_time, 0, 5) : '-' }}
                                        @if ($event->end_time)
                                            - {{ substr($event->end_time, 0, 5) }}
                                        @endif
                                    @else
                                        <span class="text-muted">Full day</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $event->title }}</strong>
                                    @if ($event->is_national_holiday)
                                        <div class="small text-muted">Hari libur/cuti bersama nasional</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="calendar-event-chip" style="background: {{ $event->color }}">{{ $event->type_label }}</span>
                                </td>
                                <td class="text-muted">{{ \Illuminate\Support\Str::limit($event->description ?: '-', 90) }}</td>
                                @if (auth()->user()->isAdmin())
                                    <td class="text-right">
                                        <span class="table-action-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editEvent{{ $event->id }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('calendar.destroy', $event) }}" data-confirm="Hapus event kalender ini?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" class="text-center text-muted py-4">Event tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($calendarEvents->hasPages())
            <div class="card-footer">
                {{ $calendarEvents->links() }}
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="eventDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailTitle">Detail Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="calendar-event-chip mb-3" id="eventDetailType" style="background:#0f5fb8">Event</span>
                <p class="mb-0 text-muted" id="eventDetailDescription">Tidak ada catatan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@if (auth()->user()->isAdmin())
    <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('calendar.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Event Kalender</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('calendar.partials.form', ['event' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($calendarEvents as $event)
        <div class="modal fade" id="editEvent{{ $event->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form method="POST" action="{{ route('calendar.update', $event) }}" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Event Kalender</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('calendar.partials.form', ['event' => $event])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            height: 'auto',
            locale: 'id',
            selectable: @json(auth()->user()->isAdmin()),
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            events: @json($events),
            dateClick: function (info) {
                const input = document.getElementById('event_date');
                if (input) {
                    input.value = info.dateStr;
                    $('#createEventModal').modal('show');
                }
            },
            eventClick: function (info) {
                if (info.event.url) {
                    return;
                }

                const type = info.event.extendedProps.type || 'Event';
                const description = info.event.extendedProps.description || 'Tidak ada catatan.';
                document.getElementById('eventDetailTitle').textContent = info.event.title;
                document.getElementById('eventDetailType').textContent = type;
                document.getElementById('eventDetailType').style.background = info.event.backgroundColor || info.event.borderColor || '#0f5fb8';
                document.getElementById('eventDetailDescription').textContent = description;
                $('#eventDetailModal').modal('show');
            }
        });

        calendar.render();
    });
</script>
@endpush
