<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'roles' => ['ver-rol', 'crear-rol', 'actualizar-rol', 'eliminar-rol'],
            'permissions' => ['ver-permisos', 'agregar-permisos', 'actualizar-permisos', 'eliminar-permisos'],
            'users' => ['ver-trabajadores', 'actualizar-trabajadores', 'eliminar-trabajadores', 'agregar-trabajadores', 'buscar-trabajadores'],
            'permisos-operarios' => ['actualizar', 'eliminar', 'agregar', 'eliminar-productos', 'buscar-productos'],
            'permisos-jefe' => ['jefe-ver'],
            'permisos-supervisor' => ['supervisor-ver'],

        ];

        foreach ($permissions as $category => $perms) {
            foreach ($perms as $perm) {
                Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            }
        }
        $roles = [
            'administrador' => array_merge(...array_values($permissions)), // Admin tiene todos los permisos
            'operario' => array_merge($permissions['permisos-operarios']),
            'jefe' => array_merge($permissions['permisos-operarios'], $permissions['permisos-jefe'], $permissions['permisos-supervisor']),
            'supervisor' => array_merge($permissions['permisos-supervisor']),

        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $validPermissions = Permission::whereIn('name', $rolePermissions)->pluck('name')->toArray();
            foreach ($validPermissions as $perm) {
                if (!$role->hasPermissionTo($perm)) {
                    $role->givePermissionTo($perm);
                }
            }
        }
        $users = [
            [
                'name' => 'administrador',
                'email' => 'administrador@gmail.com',
                'password' => '12345678',
                'role' => 'administrador'
            ],
            [
                'name' => 'supervisor',
                'email' => 'supervisor@gmail.com',
                'password' => '12345678',
                'role' => 'supervisor'
            ],
             [
                'name' => 'operario',
                'email' => 'operario@gmail.com',
                'password' => '12345678',
                'role' => 'operario'
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password']
                ]
            );
            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }

    }
}
