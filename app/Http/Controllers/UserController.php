<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a paginated, searchable list of active users.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $deletedCount = User::onlyTrashed()->count();

        return view('users.index', compact('users', 'search', 'deletedCount'));
    }

    /**
     * Show soft-deleted users with a Restore button.
     */
    public function trash(): View
    {
        $users = User::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(12);

        return view('users.trash', compact('users'));
    }

    /**
     * Change a user's role (manager / staff).
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $this->authorizeManager();

        $validated = $request->validate([
            'role' => ['required', 'in:manager,staff'],
        ], [
            'role.in' => 'Select a valid role.',
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Role for {$user->name} updated to ".ucfirst($validated['role']).'.');
    }

    /**
     * Activate or deactivate a user account.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        $this->authorizeManager();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own account status.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', "{$user->name} is now ".($user->is_active ? 'Active' : 'Inactive').'.');
    }

    /**
     * Soft delete: move the user to Trash instead of removing the record.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeManager();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', "{$user->name} moved to Trash. You can restore the account anytime.");
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore(string $id): RedirectResponse
    {
        $this->authorizeManager();

        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.trash')
            ->with('success', "{$user->name} restored successfully.");
    }

    /**
     * Permanently delete a user from the Trash.
     */
    public function forceDelete(string $id): RedirectResponse
    {
        $this->authorizeManager();

        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('users.trash')
            ->with('success', "{$user->name} permanently deleted from the Trash.");
    }

    private function authorizeManager(): void
    {
        if (! auth()->user()?->isManager()) {
            abort(403, 'Only a manager can manage user accounts.');
        }
    }
}
