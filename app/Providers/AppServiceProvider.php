<?php

namespace App\Providers;

use App\Models\CalendarEvent;
use App\Models\DailyActivity;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();

        View::composer('layouts.app', function ($view): void {
            $user = auth()->user();

            if (! $user) {
                return;
            }

            $todayReminders = CalendarEvent::query()
                ->whereDate('event_date', today())
                ->orderBy('start_time')
                ->take(5)
                ->get();

            $pendingActivities = DailyActivity::query()
                ->whereIn('status', ['Belum Mulai', 'Berjalan', 'Tertunda'])
                ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
                ->orderBy('activity_date')
                ->take(5)
                ->get();

            $view->with([
                'headerNotifications' => $todayReminders->map(fn ($event) => [
                    'title' => $event->title,
                    'meta' => $event->type_label.($event->start_time ? ' - '.substr($event->start_time, 0, 5) : ''),
                    'icon' => 'far fa-calendar-alt',
                    'url' => route('calendar.index'),
                    'color' => 'text-primary',
                ])->concat($pendingActivities->map(fn ($activity) => [
                    'title' => $activity->title,
                    'meta' => $activity->status.' - '.$activity->progress.'%',
                    'icon' => 'fas fa-clipboard-list',
                    'url' => route('activities.show', $activity),
                    'color' => $activity->status === 'Tertunda' ? 'text-warning' : 'text-info',
                ]))->take(8),
                'headerNotificationCount' => $todayReminders->count() + $pendingActivities->count(),
            ]);
        });
    }
}
