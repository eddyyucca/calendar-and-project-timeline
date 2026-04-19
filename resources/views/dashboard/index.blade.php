@extends('layouts.app', ['title' => 'Dashboard', 'pageTitle' => 'Summary Pekerjaan'])

@section('content')
@if (auth()->user()->isAdmin())
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}" class="row align-items-end">
                <div class="col-md-8 mb-2">
                    <label for="employee_id">Lihat Progress Karyawan</label>
                    <select id="employee_id" name="employee_id" class="form-control">
                        <option value="">Semua karyawan</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @selected($selectedEmployeeId === $employee->id)>
                                {{ $employee->name }} - {{ $employee->email }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2 text-md-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter mr-1"></i>Tampilkan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-users mr-1"></i>Semua
                    </a>
                </div>
            </form>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header border-0 d-flex align-items-center justify-content-between">
        <h3 class="card-title font-weight-bold"><i class="far fa-bell text-primary mr-2"></i>Notifikasi Hari Ini</h3>
        <span class="badge badge-primary">{{ $todayReminders->count() + $blocked }} item</span>
    </div>
    <div class="card-body p-0">
        <div class="row no-gutters">
            <div class="col-lg-6 border-right">
                <div class="p-3">
                    <h6 class="font-weight-bold mb-3">Event Kalender</h6>
                    <ul class="list-group list-group-flush">
                        @forelse ($todayReminders as $reminder)
                            <li class="list-group-item px-0">
                                <strong>{{ $reminder->title }}</strong>
                                <div class="small text-muted">
                                    {{ $reminder->type_label }}
                                    @if ($reminder->start_time)
                                        - {{ substr($reminder->start_time, 0, 5) }}
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">Tidak ada event hari ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-3">
                    <h6 class="font-weight-bold mb-3">Aktivitas Perlu Perhatian</h6>
                    <ul class="list-group list-group-flush">
                        @forelse ($todayActivities->whereIn('status', ['Belum Mulai', 'Berjalan', 'Tertunda'])->take(5) as $activity)
                            <li class="list-group-item px-0">
                                <a href="{{ route('activities.show', $activity) }}" class="font-weight-bold">{{ $activity->title }}</a>
                                <div class="small text-muted">{{ $activity->status }} - {{ $activity->progress }}%</div>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">Tidak ada aktivitas yang perlu perhatian hari ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $total }}</h3>
                <p>Total Aktivitas</p>
            </div>
            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
            <a href="{{ route('activities.index') }}" class="small-box-footer">Buka data <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $done }}</h3>
                <p>Selesai</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('activities.index', ['status' => 'Selesai']) }}" class="small-box-footer">Lihat selesai <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $averageProgress }}<sup style="font-size: 20px">%</sup></h3>
                <p>Rata-rata Progress</p>
            </div>
            <div class="icon"><i class="fas fa-percentage"></i></div>
            <a href="{{ route('activities.index') }}" class="small-box-footer">Pantau progress <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $blocked }}</h3>
                <p>Tertunda</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
            <a href="{{ route('activities.index', ['status' => 'Tertunda']) }}" class="small-box-footer">Cek kendala <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">Tren Aktivitas 7 Hari</h3>
            </div>
            <div class="card-body">
                <div style="height: 260px;">
                    <canvas id="dailyTrendChart"></canvas>
                </div>
            </div>
        </div>

        @if (auth()->user()->isAdmin())
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bold">Rata-rata Progress Karyawan</h3>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="employeeProgressChart"></canvas>
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header border-0 d-flex align-items-center justify-content-between">
                <h3 class="card-title font-weight-bold">Aktivitas Hari Ini</h3>
                <a href="{{ route('activities.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-1"></i>Tambah
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Aktivitas</th>
                                @if (auth()->user()->isAdmin())
                                    <th>Karyawan</th>
                                @endif
                                <th>Status</th>
                                <th style="min-width: 160px;">Progress</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($todayActivities as $activity)
                                <tr>
                                    <td>
                                        <a href="{{ route('activities.show', $activity) }}" class="font-weight-bold">{{ $activity->title }}</a>
                                        <div class="text-muted small">{{ $activity->category }} - {{ $activity->priority }}</div>
                                    </td>
                                    @if (auth()->user()->isAdmin())
                                        <td>{{ $activity->user->name }}</td>
                                    @endif
                                    <td><span class="badge badge-light border">{{ $activity->status }}</span></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-{{ $activity->progress_color }}" style="width: {{ $activity->progress }}%"></div>
                                        </div>
                                        <span class="small text-muted">{{ $activity->progress }}%</span>
                                    </td>
                                    <td><i class="far fa-comments text-primary mr-1"></i>{{ $activity->comments_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="text-center text-muted py-4">Belum ada aktivitas hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0 d-flex align-items-center justify-content-between">
                <h3 class="card-title font-weight-bold">Pengingat Hari Ini</h3>
                <a href="{{ route('calendar.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="far fa-calendar-alt mr-1"></i>Detail
                </a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse ($todayReminders as $reminder)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $reminder->title }}</strong>
                                    <div class="small text-muted">
                                        {{ $reminder->event_date->format('d/m/Y') }}
                                        @if ($reminder->start_time)
                                            - {{ substr($reminder->start_time, 0, 5) }}
                                        @endif
                                        - {{ $reminder->type_label }}
                                    </div>
                                </div>
                                <span class="badge align-self-start" style="background: {{ $reminder->color }}; color: #fff;">{{ $reminder->type_label }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada event kalender hari ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">Chart Status</h3>
            </div>
            <div class="card-body">
                <div style="height: 260px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        @if (auth()->user()->isAdmin())
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bold">Progress Per Karyawan</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($employeeSummaries as $employee)
                            @php
                                $avg = (int) round($employee->activities_avg_progress ?? 0);
                            @endphp
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('dashboard', ['employee_id' => $employee->id]) }}" class="font-weight-bold">{{ $employee->name }}</a>
                                    <span>{{ $avg }}%</span>
                                </div>
                                <div class="progress mt-2">
                                    <div class="progress-bar bg-primary" style="width: {{ $avg }}%"></div>
                                </div>
                                <div class="small text-muted mt-1">{{ $employee->activities_count }} aktivitas</div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Belum ada karyawan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">Komposisi Status</h3>
            </div>
            <div class="card-body">
                @foreach ($statusCounts as $status => $count)
                    @php
                        $percent = $total > 0 ? round(($count / $total) * 100) : 0;
                    @endphp
                    <div class="d-flex justify-content-between mb-1">
                        <span>{{ $status }}</span>
                        <strong>{{ $count }}</strong>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">Fokus Terbaru</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse ($latestActivities as $activity)
                        <li class="list-group-item">
                            <a href="{{ route('activities.show', $activity) }}" class="font-weight-bold">{{ $activity->title }}</a>
                            <div class="small text-muted">
                                {{ $activity->activity_date->format('d/m/Y') }} - {{ $activity->user->name }} - {{ $activity->progress }}% - {{ $activity->comments_count }} komentar
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const statusChart = @json($statusChartData);
    const dailyTrendChart = @json($dailyTrendChartData);
    const employeeProgressChart = @json($employeeProgressChartData);
    const gridColor = 'rgba(15, 95, 184, .12)';
    const blueMain = '#0f5fb8';

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusChart.labels,
            datasets: [{
                data: statusChart.data,
                backgroundColor: ['#64748b', '#0f5fb8', '#f59e0b', '#16a34a'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '62%'
        }
    });

    new Chart(document.getElementById('dailyTrendChart'), {
        type: 'line',
        data: {
            labels: dailyTrendChart.labels,
            datasets: [{
                label: 'Aktivitas',
                data: dailyTrendChart.data,
                borderColor: blueMain,
                backgroundColor: 'rgba(15, 95, 184, .14)',
                fill: true,
                tension: .35,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    if (document.getElementById('employeeProgressChart')) {
        new Chart(document.getElementById('employeeProgressChart'), {
            type: 'bar',
            data: {
                labels: employeeProgressChart.labels,
                datasets: [{
                    label: 'Progress',
                    data: employeeProgressChart.data,
                    backgroundColor: '#0f5fb8',
                    borderRadius: 5
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        min: 0,
                        max: 100,
                        ticks: {
                            callback: value => value + '%'
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>
@endpush
