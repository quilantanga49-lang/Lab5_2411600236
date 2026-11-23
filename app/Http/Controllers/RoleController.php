<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Switch the signed-in user between Manager and Staff views.
     * This demonstrates role-based access in the Laravel MVC system.
     */
    public function switch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:manager,staff'],
        ], [
            'role.in' => 'Select a valid role.',
        ]);

        $request->user()->update(['role' => $validated['role']]);

        return back()->with(
            'success',
            'Role switched to '.ucfirst($validated['role']).'.'
        );
    }
}
