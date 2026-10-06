<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['departemen', 'roles'])->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
                  ->orWhere('username', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users      = $query->paginate(15)->withQueryString();
        $roles      = Role::all();
        $departemen = Departemen::all();
        return view('user-management.user.index', compact('users', 'roles', 'departemen'));
    }

    public function create()
    {
        $roles      = Role::all();
        $departemen = Departemen::all();
        return view('user-management.user.create', compact('roles', 'departemen'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'      => 'required|string|unique:users|max:50',
            'nama_lengkap'  => 'required|string|max:150',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|exists:roles,name',
            'departemen_id' => 'nullable|exists:departemen,id',
        ]);

        $user = User::create([
            'username'      => $validated['username'],
            'nama_lengkap'  => $validated['nama_lengkap'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'departemen_id' => $validated['departemen_id'] ?? null,
            'status'        => true,
        ]);
        $user->assignRole($validated['role']);

        activity()->causedBy(auth()->user())->log("Menambahkan user baru: {$user->username}");
        return redirect()->route('user-management.user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles      = Role::all();
        $departemen = Departemen::all();
        return view('user-management.user.edit', compact('user', 'roles', 'departemen'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama_lengkap'  => 'required|string|max:150',
            'email'         => 'required|email|unique:users,email,'.$user->id,
            'role'          => 'required|exists:roles,name',
            'departemen_id' => 'nullable|exists:departemen,id',
            'status'        => 'boolean',
            'password'      => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'nama_lengkap'  => $validated['nama_lengkap'],
            'email'         => $validated['email'],
            'departemen_id' => $validated['departemen_id'] ?? null,
            'status'        => $request->boolean('status'),
        ];
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->syncRoles([$validated['role']]);

        activity()->causedBy(auth()->user())->log("Memperbarui user: {$user->username}");
        return redirect()->route('user-management.user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->update(['status' => false]);
        return back()->with('success', 'Pengguna berhasil dinonaktifkan.');
    }
}
