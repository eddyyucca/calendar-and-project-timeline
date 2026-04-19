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

    .calendar-reminder-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: .75rem;
    }

    .calendar-reminder-item {
        min-height: 92px;
        border: 1px solid #e5edf7;
        border-radius: .6rem;
        padding: .85rem;
        background: #fff;
    }

    .calendar-reminder-item strong {
        display: block;
        line-height: 1.25;
        margin-bottom: .4rem;
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
        <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="card-title font-weight-bold">Pengingat Terdekat</h3>
            <span class="text-muted small">{{ $calendarEvents->where('event_date', '>=', today())->count() }} event mendatang</span>
        </div>
        <div class="card-body">
            <div class="calendar-reminder-grid">
                @forelse ($calendarEvents->where('event_date', '>=', today())->take(8) as $event)
                    <div class="calendar-reminder-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="pr-2">
                                <strong>{{ $event->title }}</strong>
                                <div class="small text-muted">
                                    {{ $event->event_date->format('d/m/Y') }}
                                    @if ($event->start_time)
                                        - {{ substr($event->start_time, 0, 5) }}
                                    @endif
                                </div>
                                <span class="calendar-event-chip mt-2" style="background: {{ $event->color }}">{{ $event->type_label }}</span>
                            </div>
                            @if (auth()->user()->isAdmin())
                                <div class="table-action-group">
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
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-muted">Tidak ada pengingat terdekat.</div>
                @endforelse
            </div>
        </div>
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
