<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\FamilyCard;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $family_members = FamilyMember::orderBy('family_card_id', 'asc')->get();
        $family_cards = FamilyCard::with('family_member')->get();
        // dd($family_cards);
        return view('dashboard.family-member.index', ['family_cards' => $family_cards, 'family_members' => $family_members]);
    }
    public function store(Request $request)
    {
        $nik_unavailable = FamilyMember::where('nik', request('nik'))->first();
        if ($nik_unavailable != null) {
            return redirect()->back()->with("ERR", "NIK tidak dapat digunakan");
        }

        FamilyMember::create([
            'family_card_id'  => request('family_card_id'),
            'family_member_name' => request('family_member_name'),
            'family_status' => request('family_status'),
            'nik' => request('nik'),
            'gender' => request('gender'),
            'birth_place' => request('birth_place'),
            'birth_date' => request('birth_date'),
            'religious' => request('religious'),
            'citizenship' => request('citizenship'),
            'education' => request('education'),
            'marital_status' => request('marital_status'),
            'address' => request('address'),
            'job' => request('job'),
        ]);

        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Tambah anggota keluarga'
        ]);

        return redirect()->back()->with("OK", "Berhasil menambahkan data");
    }


    public function edit($id)
    {
        $family_member = FamilyMember::with('family_card')->findOrFail($id);
        return $family_member;
    }
    public function update(Request $request, FamilyMember $familyMember)
    {
        
        $familyMember->update([
            'family_member_name' => request('family_member_name'),
            'family_status' => request('family_status'),
            'nik' => request('nik'),
            'gender' => request('gender'),
            'birth_place' => request('birth_place'),
            'birth_date' => request('birth_date'),
            'religious' => request('religious'),
            'citizenship' => request('citizenship'),
            'education' => request('education'),
            'marital_status' => request('marital_status'),
            'address' => request('address'),
            'job' => request('job'),
            'status' => request('status')
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Ubah anggota keluarga'
        ]);

        return redirect()->back()->with("OK", "Berhasil mengubah data");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FamilyMember $familyMember)
    {
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Hapus anggota keluarga'
        ]);
        $familyMember->delete();
        return redirect()->back()->with("OK", "Berhasil menghapus data");
    }
}
