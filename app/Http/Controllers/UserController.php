<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('created_at', 'desc')->get();
        $roles = \App\Models\Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $newRole = \App\Models\Role::find($request->role_id);

        // Prevent admin from downgrading themselves
        if ($user->id === auth()->id() && $user->isAdmin() && $newRole->name !== 'admin') {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role_id' => $request->role_id]);

        return back()->with('success', "Role updated for {$user->name} to {$newRole->description}.");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'daily_rate' => 'nullable|numeric|min:0',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role_id' => $request->role_id,
            'daily_rate' => $request->daily_rate ?? 0,
        ]);

        return back()->with('success', 'User created successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}