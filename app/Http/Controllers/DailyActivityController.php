<?php

namespace App\Http\Controllers;

use App\Models\DailyActivity;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DailyActivityController extends Controller
{
    public function index(Request $request): View
    {
        $query = DailyActivity::query()
            ->with('user')
            ->withCount('comments')
            ->when(! $request->user()->isAdmin(), fn ($query) => $query->where('user_id', $request->user()->id))
            ->latest('activity_date')
            ->latest();

        if ($request->user()->isAdmin() && $request->filled('employee_id')) {
            $query->where('user_id', $request->input('employee_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        return view('activities.index', [
            'activities' => $query->paginate(10)->withQueryString(),
            'statuses' => DailyActivity::STATUSES,
            'employees' => $request->user()->isAdmin()
                ? User::query()->orderBy('name')->get(['id', 'name', 'email'])
                : collect(),
        ]);
    }

    public function create(): View
    {
        return view('activities.form', [
            'activity' => new DailyActivity([
                'activity_date' => today(),
                'status' => 'Belum Mulai',
                'priority' => 'Normal',
                'category' => 'Operasional',
                'progress' => 0,
            ]),
            'statuses' => DailyActivity::STATUSES,
            'priorities' => DailyActivity::PRIORITIES,
            'categories' => DailyActivity::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $activity = DailyActivity::create($this->validatedData($request) + [
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('activities.show', $activity)
            ->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function show(Request $request, DailyActivity $activity): View
    {
        $this->authorizeAccess($request, $activity);

        $activity->load(['comments.user', 'user']);

        return view('activities.show', compact('activity'));
    }

    public function edit(Request $request, DailyActivity $activity): View
    {
        $this->authorizeAccess($request, $activity);

        return view('activities.form', [
            'activity' => $activity,
            'statuses' => DailyActivity::STATUSES,
            'priorities' => DailyActivity::PRIORITIES,
            'categories' => DailyActivity::CATEGORIES,
        ]);
    }

    public function update(Request $request, DailyActivity $activity): RedirectResponse
    {
        $this->authorizeAccess($request, $activity);

        $activity->update($this->validatedData($request));

        return redirect()
            ->route('activities.show', $activity)
            ->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(Request $request, DailyActivity $activity): RedirectResponse
    {
        $this->authorizeAccess($request, $activity);

        $activity->delete();

        return redirect()
            ->route('activities.index')
            ->with('success', 'Aktivitas berhasil dihapus.');
    }

    public function comment(Request $request, DailyActivity $activity): RedirectResponse
    {
        $this->authorizeAccess($request, $activity);

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $activity->comments()->create($data + [
            'user_id' => $request->user()->id,
        ]);

        $status = match (true) {
            $data['progress'] >= 100 => 'Selesai',
            $activity->status === 'Belum Mulai' && $data['progress'] > 0 => 'Berjalan',
            default => $activity->status,
        };

        $activity->update([
            'progress' => $data['progress'],
            'status' => $status,
            'completed_at' => $data['progress'] >= 100 ? now() : null,
        ]);

        return back()->with('success', 'Komentar dan persentase progress berhasil ditambahkan.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'activity_date' => ['required', 'date'],
            'category' => ['required', Rule::in(DailyActivity::CATEGORIES)],
            'priority' => ['required', Rule::in(DailyActivity::PRIORITIES)],
            'status' => ['required', Rule::in(DailyActivity::STATUSES)],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
            'blocker' => ['nullable', 'string'],
        ]);

        $data['completed_at'] = $data['status'] === 'Selesai' || (int) $data['progress'] === 100 ? now() : null;

        return $data;
    }

    private function authorizeAccess(Request $request, DailyActivity $activity): void
    {
        abort_unless($request->user()->isAdmin() || $activity->user_id === $request->user()->id, 403);
    }
}
