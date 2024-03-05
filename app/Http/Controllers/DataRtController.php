<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\DataRt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DataRtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data_rts['data_rts'] = DataRt::orderBy('id', 'asc')->get();
        return view('dashboard.data-rt.index', $data_rts);
    }

    public function store(Request $request)
    {
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Tambah data rt'
        ]);

        if ($request->hasFile('logo_rt')) {
            $image = $request->file('logo_rt');
            $filename = $image->getClientOriginalName();
            $save = $image->storeAs('public/picture', $filename);
            $logo_rt = url('/') . '/storage/picture/' . $filename;
        }
        if ($request->hasFile('sign_rt')) {
            $image2 = $request->file('sign_rt');
            $filename2 = $image2->getClientOriginalName();
            $save = $image2->storeAs('public/picture', $filename2);
            $sign_rt = url('/') . '/storage/picture/' . $filename2;
        }


        DataRt::create([
            'name_lead_rt' => request('name_lead_rt'),
            'logo_rt' => $logo_rt,
            'sign_rt' => $sign_rt
        ]);
        return redirect()->back()->with('OK', 'Berhasil menambahkan data');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dataRt = DataRt::findOrFail($id);
        return $dataRt;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataRt $dataRt)
    {
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Ubah data rt'
        ]);


        if ($request->hasFile('logo_rt')) {

            $image = $request->file('logo_rt');
            $filename = $image->getClientOriginalName();
            $save = $image->storeAs('public/picture', $filename);
            $logo = url('/') . '/storage/picture/' . $filename;
        }
        if ($request->hasFile('sign_rt')) {

            $image2 = $request->file('sign_rt');
            $filename2 = $image2->getClientOriginalName();
            $save = $image2->storeAs('public/picture', $filename2);
            $sign = url('/') . '/storage/picture/' . $filename2;
        }


        $dataRt->update([
            'name_lead_rt' => request('name_lead_rt'),
            'logo_rt' => $logo,
            'sign_rt' => $sign
        ]);
        return redirect()->back()->with('OK', 'Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataRt $dataRt)
    {
        if ($dataRt->logo_rt) {
            Storage::delete($dataRt->logo_rt);
        }
        if ($dataRt->sign_rt) {
            Storage::delete($dataRt->sign_rt);
        }
        DataRt::destroy($dataRt->id);
        return redirect()->back()->with('OK', 'Data berhasil dihapus!');
    }
}
