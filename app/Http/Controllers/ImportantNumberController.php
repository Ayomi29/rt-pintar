<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\ImportantNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImportantNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $important_numbers['important_numbers'] = ImportantNumber::orderBy('id', 'asc')->get();
        return view('dashboard.important-number.index', $important_numbers);
    }


    public function store(Request $request)
    {
        $phone_number_unavailable = ImportantNumber::where('phone_number', request('phone_number'))->first();
        if ($phone_number_unavailable != null) {
            return redirect()->back()->with("ERR", "Nomor telepon sudah tersedia");
        }
        ImportantNumber::create([
            'name' => request('name'),
            'phone_number' => request('phone_number')
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Tambah data nomor penting'
        ]);
        return redirect()->back()->with('OK', "Berhasil menambahkan data");
    }
    public function edit($number)
    {
        $important_number = ImportantNumber::findOrFail($number);
        return $important_number;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImportantNumber $importantNumber)
    {
        $phone_number_unavailable = ImportantNumber::where('phone_number', request('phone_number'))->first();
        if ($phone_number_unavailable != null) {
            if ($importantNumber->id != $phone_number_unavailable->id)
                return redirect()->back()->with("ERR", "Nomor telepon sudah tersedia");
        }
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Ubah data nomor penting'
        ]);

        $importantNumber->update([
            'name' => request('name'),
            'phone_number' => request('phone_number')
        ]);
        return redirect()->back()->with('OK', "Berhasil mengubah data");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImportantNumber $importantNumber)
    {
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Hapus data nomor penting'
        ]);
        $importantNumber->delete();
        return redirect()->back()->with('OK', "Berhasil menghapus data");
    }
}
