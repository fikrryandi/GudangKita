<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users', 'permissions')->get();
        $permissions = Permission::all()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? 'other');
        return view('user-management.role.index', compact('roles', 'permissions'));
    }

    public function getPermissions(Role $role)
    {
        return response()->json($role->permissions->pluck('name'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:50',
        ], ['name.unique' => 'Nama role sudah ada.']);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return back()->with('success', "Role '{$role->name}' berhasil dibuat.");
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions ?? []);
        }

        return back()->with('success', "Role '{$role->name}' berhasil diperbarui.");
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', "Role '{$role->name}' tidak bisa dihapus karena masih digunakan oleh {$role->users()->count()} pengguna.");
        }

        $name = $role->name;
        $role->delete();
        return back()->with('success', "Role '{$name}' berhasil dihapus.");
    }
}
