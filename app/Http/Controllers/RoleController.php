<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = \App\Models\Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'label' => 'required|string',
            'access_guests' => 'nullable|boolean',
        ]);

        $permissions = [];
        if ($request->boolean('access_guests')) {
            $permissions['access_guests'] = true;
        }

        \App\Models\Role::create([
            'name' => $validated['name'],
            'label' => $validated['label'],
            'permissions' => $permissions,
        ]);

        return redirect()->route('roles.index')->with('status', 'Rol creado con éxito.');
    }

    public function edit(\App\Models\Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, \App\Models\Role $role)
    {
        $validated = $request->validate([
            'label' => 'required|string',
            'access_guests' => 'nullable|boolean',
        ]);

        $permissions = $role->permissions ?? [];
        
        // Actualizar permiso invitado
        if ($request->boolean('access_guests')) {
            $permissions['access_guests'] = true;
        } else {
            $permissions['access_guests'] = false;
        }

        $role->update([
            'label' => $validated['label'],
            'permissions' => $permissions,
        ]);

        return redirect()->route('roles.index')->with('status', 'Rol actualizado con éxito.');
    }

    public function destroy(\App\Models\Role $role)
    {
        if ($role->users()->exists()) {
            return back()->with('error', 'No se puede eliminar un rol que tiene usuarios asignados.');
        }
        $role->delete();
        return redirect()->route('roles.index')->with('status', 'Rol eliminado.');
    }
}
