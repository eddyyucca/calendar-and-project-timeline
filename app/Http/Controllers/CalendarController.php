<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\DailyActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $calendarEvents = CalendarEvent::query()
            ->with('user')
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        $activities = DailyActivity::query()
            ->with('user')
            ->when(! $request->user()->isAdmin(), fn ($query) => $query->where('user_id', $request->user()->id))
            ->orderBy('activity_date')
            ->get();

        $events = $calendarEvents->map(fn (CalendarEvent $event) => [
            'id' => 'event-'.$event->id,
            'title' => $event->title,
            'start' => $event->event_date->format('Y-m-d').($event->start_time ? 'T'.$event->start_time : ''),
            'end' => $event->end_time ? $event->event_date->format('Y-m-d').'T'.$event->end_time : null,
            'color' => $event->color,
            'extendedProps' => [
                'type' => $event->type_label,
                'description' => $event->description,
            ],
        ])->merge($activities->map(fn (DailyActivity $activity) => [
            'id' => 'activity-'.$activity->id,
            'title' => $activity->user->name.' - '.$activity->title.' ('.$activity->progress.'%)',
            'start' => $activity->activity_date->format('Y-m-d'),
            'url' => route('activities.show', $activity),
            'color' => $activity->progress >= 100 ? '#16a34a' : '#64748b',
            'extendedProps' => [
                'type' => 'Aktivitas',
                'description' => $activity->status.' - '.$activity->category,
            ],
        ]))->values();

        return view('calendar.index', [
            'calendarEvents' => $calendarEvents,
            'events' => $events,
            'types' => CalendarEvent::TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        CalendarEvent::create($this->validatedData($request) + [
            'user_id' => $request->user()->id,
            'is_national_holiday' => in_array($request->input('type'), ['holiday', 'leave'], true),
        ]);

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Event kalender berhasil ditambahkan.');
    }

    public function update(Request $request, CalendarEvent $calendarEvent): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $calendarEvent->update($this->validatedData($request) + [
            'is_national_holiday' => in_array($request->input('type'), ['holiday', 'leave'], true),
        ]);

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Event kalender berhasil diperbarui.');
    }

    public function destroy(Request $request, CalendarEvent $calendarEvent): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $calendarEvent->delete();

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Event kalender berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(CalendarEvent::TYPES)],
            'event_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->isAdmin(), 403);
    }
}
