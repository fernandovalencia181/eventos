<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminStaffController extends Controller
{
    public function index()
    {
        // Obtener usuarios que tienen rol 'staff' (ya sea por string o ID)
        $staffUsers = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'staff');
        })->orWhere('rol', 'staff')->with(['role', 'evento'])->get();

        return view('admin.staff.index', compact('staffUsers'));
    }

    public function create()
    {
        $eventos = \App\Models\Evento::where('fecha', '>=', now())->get();
        return view('admin.staff.create', compact('eventos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8',
            'evento_id' => 'required|exists:eventos,id',
        ]);

        // Obtener o crear rol staff
        $staffRole = \App\Models\Role::firstOrCreate(['name' => 'staff'], ['label' => 'Staff']);

        $permissions = [];
        if ($request->has('access_guests')) {
            $permissions['access_guests'] = true;
        }

        \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'rol' => 'staff', // para compatibilidad antigua
            'role_id' => $staffRole->id,
            'evento_id' => $validated['evento_id'],
            'permissions' => $permissions,
        ]);

        return redirect()->route('admin.staff.index')->with('status', 'Miembro de Staff creado correctamente.');
    }

    public function edit(\App\Models\User $staff)
    {
        $eventos = \App\Models\Evento::where('fecha', '>=', now())->get();
        return view('admin.staff.edit', compact('staff', 'eventos'));
    }

    public function update(Request $request, \App\Models\User $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8',
            'evento_id' => 'required|exists:eventos,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'evento_id' => $validated['evento_id'],
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        // Handle permissions
        $permissions = $staff->permissions ?? [];
        if ($request->has('access_guests')) {
             $permissions['access_guests'] = true;
        } else {
             $permissions['access_guests'] = false;
        }
        $data['permissions'] = $permissions;

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('status', 'Miembro de Staff actualizado.');
    }

    public function destroy(\App\Models\User $staff)
    {
        // Precaución: Borrar usuario
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('status', 'Cuenta de staff eliminada.');
    }

    public function togglePermission(Request $request, \App\Models\User $staff)
    {
        $request->validate([
            'permission' => 'required|string',
            'value' => 'required|boolean'
        ]);

        $permissions = $staff->permissions ?? [];
        $permissions[$request->permission] = $request->value;
        
        $staff->permissions = $permissions;
        $staff->save();

        return response()->json(['success' => true]);
    }
}
