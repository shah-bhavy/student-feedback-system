<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->isAdmin(), 403);

        return view('admin.users.index', [
            'users' => User::query()->withTrashed()->latest()->paginate(10),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()?->isAdmin(), 403);

        if ($user->isAdmin() && $request->input('role') !== 'admin') {
            return back()->with('status', 'Admin users cannot be demoted from this panel.');
        }

        $validated = $request->validate([
            'role' => ['required', 'in:admin,principal,faculty,student'],
        ]);

        $user->update($validated);

        return back()->with('status', 'User role updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless(request()->user()?->isAdmin(), 403);

        if ($user->isAdmin()) {
            return back()->with('status', 'Admin users cannot be moved to trash.');
        }

        $user->delete();

        return back()->with('status', 'User moved to trash.');
    }

    public function restore(Request $request, int $userId): RedirectResponse
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $user = User::withTrashed()->findOrFail($userId);

        if ($user->isAdmin()) {
            return back()->with('status', 'Admin users are protected and do not require restore.');
        }

        $user->restore();

        return back()->with('status', 'User restored.');
    }
}
