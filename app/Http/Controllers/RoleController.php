<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('users')->orderBy('id', 'asc')->get();
        $users = User::whereDoesntHave('admin')->whereDoesntHave('pengurus')->orderBy('id', 'asc')->get();
        return view('dashboard.role.index', ['users' => $users, 'roles' => $roles]);
    }
    public function store(Request $request)
    {
        Role::create([
            'user_id' => request('user_id'),
            'role_name' => request('role_name')
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Tambah role user'
        ]);
        return redirect()->back()->with('OK', 'Berhasil menambahkan data');
    }
    public function edit($peran)
    {
        $role = Role::findOrFail($peran);
        return $role;
    }
    public function update(Request $request, Role $role)
    {
        $unavailable_role = Role::where('user_id', request('user_id'))->first();
        if ($unavailable_role != null) {
            if ($role->id != $unavailable_role->id) {
                return redirect()->back()->with("ERR", "User ini sudah memiliki role");
            }
        }
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Ubah role user'
        ]);
        $role->update([
            'user_id' => request('user_id'),
            'role_name' => request('role_name')
        ]);

        return redirect()->back()->with('OK', 'Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Hapus role user'
        ]);
        $role->delete();

        return redirect()->back()->with('OK', 'Berhasil menghapus data');
    }
}
