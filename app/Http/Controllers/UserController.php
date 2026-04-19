<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $users = User::query()
            ->withCount('activities')
            ->withAvg('activities', 'progress')
            ->orderBy('name')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(Request $request): View
    {
        $this->authorizeAdmin($request);

        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,employee'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorizeAdmin($request);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,employee'],
        ]);

        if ($request->user()->is($user) && $data['role'] !== 'admin') {
            return back()
                ->withErrors(['role' => 'Superadmin tidak bisa menurunkan role akun sendiri.'])
                ->withInput();
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => $data['password'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Password user berhasil direset.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->isAdmin(), 403);
    }
}
