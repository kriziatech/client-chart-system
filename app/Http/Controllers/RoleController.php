<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = \App\Models\Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'description' => 'required|string|max:255',
            'type' => 'required|in:admin,editor,viewer',
        ]);

        \App\Models\Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function show(string $id)
    {
    //
    }

    public function edit(string $id)
    {
    //
    }

    public function update(Request $request, string $id)
    {
    //
    }

    public function destroy(\App\Models\Role $role)
    {
        if (in_array($role->name, ['admin', 'editor', 'viewer'])) {
            return back()->with('error', 'Cannot delete default roles.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role assigned to users.');
        }

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}