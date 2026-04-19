<?php

namespace App\Http\Controllers;

use App\Models\DailyActivity;
use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $employees = $user->isAdmin()
            ? User::query()->orderBy('name')->get(['id', 'name', 'email', 'role'])
            : collect([$user]);

        $selectedEmployeeId = $user->isAdmin() && $request->filled('employee_id')
            ? (int) $request->input('employee_id')
            : null;

        $baseQuery = DailyActivity::query()
            ->with('user')
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
            ->when($selectedEmployeeId, fn ($query) => $query->where('user_id', $selectedEmployeeId));

        $total = (clone $baseQuery)->count();
        $done = (clone $baseQuery)->where('status', 'Selesai')->count();
        $active = (clone $baseQuery)->whereIn('status', ['Belum Mulai', 'Berjalan'])->count();
        $blocked = (clone $baseQuery)->where('status', 'Tertunda')->count();
        $averageProgress = (int) round((clone $baseQuery)->avg('progress') ?? 0);

        $todayActivities = (clone $baseQuery)
            ->withCount('comments')
            ->whereDate('activity_date', today())
            ->latest('updated_at')
            ->get();

        $latestActivities = (clone $baseQuery)
            ->withCount('comments')
            ->latest('activity_date')
            ->latest('updated_at')
            ->take(6)
            ->get();

        $statusSummary = DailyActivity::STATUSES;
        $statusCounts = collect($statusSummary)->mapWithKeys(fn (string $status) => [
            $status => (clone $baseQuery)->where('status', $status)->count(),
        ]);

        $employeeSummaries = $user->isAdmin()
            ? User::query()
                ->withCount('activities')
                ->withAvg('activities', 'progress')
                ->orderBy('name')
                ->get()
            : collect();

        $trendPeriod = collect(CarbonPeriod::create(today()->subDays(6), today()));
        $dailyTrendLabels = $trendPeriod->map(fn ($date) => $date->format('d/m'))->values();
        $dailyTrendData = $trendPeriod->map(fn ($date) => (clone $baseQuery)
            ->whereDate('activity_date', $date)
            ->count()
        )->values();

        $statusChartData = [
            'labels' => $statusCounts->keys()->values(),
            'data' => $statusCounts->values(),
        ];

        $employeeProgressChartData = [
            'labels' => $employeeSummaries->take(10)->pluck('name')->values(),
            'data' => $employeeSummaries->take(10)->map(fn ($employee) => (int) round($employee->activities_avg_progress ?? 0))->values(),
        ];

        $dailyTrendChartData = [
            'labels' => $dailyTrendLabels,
            'data' => $dailyTrendData,
        ];

        $todayReminders = CalendarEvent::query()
            ->whereDate('event_date', today())
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'employees',
            'selectedEmployeeId',
            'employeeSummaries',
            'statusChartData',
            'employeeProgressChartData',
            'dailyTrendChartData',
            'todayReminders',
            'total',
            'done',
            'active',
            'blocked',
            'averageProgress',
            'todayActivities',
            'latestActivities',
            'statusCounts'
        ));
    }
}
