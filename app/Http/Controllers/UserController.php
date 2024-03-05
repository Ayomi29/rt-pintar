<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereHas('family_member', function ($q) {
            $q->whereHas('family_card', function ($q1) {
                $q1->whereHas('house');
            });
        })->orderBy('id', 'asc')->get();

        $family_members = FamilyMember::whereDoesntHave('user')->orderBy('nik', 'asc')->get();

        return view('dashboard.user.index', ['users' => $users, 'family_members' => $family_members]);
    }

    public function store(Request $request)
    {
        $family_member = FamilyMember::where('nik', request('nik'))
            ->whereHas('family_card', function ($q1) {
                $q1->whereHas('house');
            })->first();
        // dd($family_member);

        $unavailable_phone_number = User::where('phone_number', request('phone_number'))->first();
        if ($unavailable_phone_number != null) {
            return redirect()->back()->with("ERR", "Nomor telepon tidak dapat digunakan");
        }

        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Tambah warga'
        ]);

        $user = User::create([
            'email' => request('email'),
            'phone_number' => request('phone_number'),
            'password' => bcrypt(request('password'))
        ]);

        $family_member->update([
            'user_id'  => $user->id,
            'verified' => '1'

        ]);

        return redirect()->back()->with("OK", "Berhasil menambahkan data");
    }

    public function edit($warga)
    {
        $user = User::with('family_member')->findOrFail($warga);

        return $user;
    }
    public function update(Request $request, User $user)
    {
        $unavailable_phone_number = User::where('phone_number', request('phone_number'))->first();
        if ($unavailable_phone_number != null) {
            if ($user->id != $unavailable_phone_number->id) {
                return redirect()->back()->with("ERR", "Nomor telepon tidak dapat digunakan");
            }
        }

        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Ubah warga'
        ]);

        $user->update([
            'phone_number' => request('phone_number'),
            'password' => bcrypt(request('password'))
        ]);

        $user->family_member->update([
            'status' => request('status'),
        ]);

        return redirect()->back()->with("OK", "Berhasil mengubah data");
    }


    public function destroy(User $user)
    {
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Hapus warga'
        ]);
        $user->family_member->update([
            'user_id' => null
        ]);
        $user->delete();

        return redirect()->back()->with("OK", "Berhasil menghapus data");
    }
}
