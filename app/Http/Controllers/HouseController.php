<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\FamilyCard;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $house = House::whereHas('family_card', function ($q) {
            $q->where('status', 'aktif');
        })->get();
        $empty_house = House::whereDoesntHave('family_card')->get();
        return view('dashboard.house.index', ['house' => $house, 'empty_house' => $empty_house]);
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $house_number_unavailable = House::where('house_number', request('house_number'))->first();
        if ($house_number_unavailable != null) {
            return redirect()->back()->with("ERR", "Nomor rumah tidak dapat digunakan");
        }
        $house = House::create([
            'house_number' => request('house_number'),
        ]);

        if ($request->filled('family_card_number')) {
            FamilyCard::create([
                'house_id' => $house->id,
                'family_card_number' => request('family_card_number'),
                'status' => 'aktif'
            ]);
        }

        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Tambah rumah'
        ]);

        return redirect()->back()->with("OK", "Berhasil menambahkan data");
    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($rumah)
    {
        $house = House::findOrFail($rumah);

        return $house;
    }

    public function update(Request $request, House $house)
    {
        $house_number_unvailable = House::where('house_number', request('house_number'))->first();
        if ($house_number_unvailable != null) {
            if ($house->id != $house_number_unvailable->id) {
                return redirect()->back()->with("ERR", "Nomor rumah tidak dapat digunakan");
            }
        }
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Ubah rumah'
        ]);

        $house->update([
            'house_number'  => request('house_number'),
            'longitude' => request('longitude'),
            'latitude' => request('latitude')
        ]);

        return redirect()->back()->with("OK", "Berhasil mengubah data");
    }
}
