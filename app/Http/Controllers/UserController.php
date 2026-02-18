<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Buscador de texto
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Filtro por Rol (opcional, pero útil)
        if ($request->has('rol') && $request->rol != '') {
            if ($request->rol === 'user') {
                // Buscamos tanto 'user' como 'usuario' por inconsistencias en BD
                $query->whereIn('rol', ['user', 'usuario']);
            } else {
                $query->where('rol', $request->rol);
            }
        }

        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'rol' => 'required|in:admin,user,staff',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('status', 'Usuario actualizado correctamente.');
    }
}
