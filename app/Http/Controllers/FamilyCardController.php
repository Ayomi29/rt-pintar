<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\FamilyCard;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $family_cards = FamilyCard::orderBy('id', 'asc')->get();
        $houses = House::get();
        return view('dashboard.family-card.index', ['family_cards' => $family_cards, 'houses' => $houses]);
    }


    public function store(Request $request)
    {
        $family_card_number_unavailable = FamilyCard::where('family_card_number', request('family_card_number'))->first();
        if ($family_card_number_unavailable != null) {
            return redirect()->back()->with("ERR", "Nomor KK sudah tersedia");
        }
        FamilyCard::create([
            'house_id' => request('house_id'),
            'family_card_number' => request('family_card_number'),
            'status' => 'aktif'
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Tambah data KK'
        ]);
        return redirect()->back()->with('OK', "Berhasil menambahkan data");
    }

    public function edit($familyCard)
    {
        $family_card = FamilyCard::findOrFail($familyCard);
        return $family_card;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FamilyCard $familyCard)
    {

        $family_card_number_unavailable = FamilyCard::where('family_card_number', request('family_card_number'))->first();
        if ($family_card_number_unavailable != null) {
            if ($familyCard->id != $family_card_number_unavailable->id)
                return redirect()->back()->with("ERR", "Nomor KK tidak bisa digunakan");
        }
        $familyCard->update([
            'house_id' => request('house_id'),
            'family_card_number' => request('family_card_number'),
            'status' => request('status')
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Ubah data KK'
        ]);
        return redirect()->back()->with('OK', "Berhasil mengubah data");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FamilyCard $familyCard)
    {
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Hapus data KK'
        ]);
        $familyCard->delete();
        return redirect()->back()->with('OK', "Berhasil menghapus data");
    }
}
