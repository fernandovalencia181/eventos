<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear roles base
        $admin = \App\Models\Role::firstOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrador', 'permissions' => []]
        );

        $staff = \App\Models\Role::firstOrCreate(
            ['name' => 'staff'],
            ['label' => 'Staff', 'permissions' => ['access_guests' => true]]
        );

        $user = \App\Models\Role::firstOrCreate(
            ['name' => 'user'],
            ['label' => 'Usuario', 'permissions' => []]
        );

        // 2. Migrar usuarios existentes (rol string -> role_id)
        $users = \App\Models\User::whereNull('role_id')->get();
        foreach ($users as $u) {
            // Asumiendo que el campo 'rol' tiene 'admin', 'staff', o 'user'
            if ($u->rol === 'admin') {
                $u->role_id = $admin->id;
            } elseif ($u->rol === 'staff') {
                $u->role_id = $staff->id;
            } else {
                $u->role_id = $user->id;
            }
            $u->save();
        }
    }
}
