<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\DashboardNotification;
use App\Models\Donation;
use App\Models\DonationBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiDonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donation = Donation::orderBy('created_at', 'desc')->get();

        if (count($donation) < 1) {
            $donation = null;
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data iuran';
        $data = ['iuran' => $donation];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $save = $image->storeAs('public/picture', $image);
            // $img = url('/') . '/storage/picture/' . $filename2;
        }

        $donation = Donation::create([
            'title' => request('title'),
            'description' => request('description'),
            'nominal' => request('nominal'),
            'image' => $save
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Membuat iuran'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah data';
        return response()->json(compact('status', 'status_code', 'message', 'donation'), 200);
    }

    public function edit($iuran)
    {
        $donation = Donation::findOrFail($iuran);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'donation'), 200);
    }


    public function update(Request $request, $id)
    {
        $donation = Donation::find($id);
        if ($request->image) {
            $donation->image = $request->file('image');
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $request->file('image')->store('public/picture');
            $filename = $request->file('image')->hashName();
            // $img = url('/') . '/storage/picture/' . $filename;
            $img = '/storage/picture/' . $filename;
        }
        $donation->update([
            'title' => request('title'),
            'description' => request('description'),
            'nominal' => request('nominal'),
            'image' => $img
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Mengubah data iuran'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah data';
        return response()->json(compact('status', 'status_code', 'message', 'donation'), 200);
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Menghapus data iuran'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menghapus data';
        return response()->json(compact('status', 'status_code', 'message', 'donation'), 200);
    }
    public function show($id)
    {
        $donation = Donation::findOrFail($id);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan detail data iuran';
        $data = ['iuran' => $donation];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
    public function storeBill(Request $request, $id)
    {
        if (request('nominal') == null || request('nominal') == '' || request('file') == null || request('file') == '') {
            $status = 'error';
            $status_code = 400;
            $message = 'Semua kolom wajib diisi';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }

        $save = $request->file('file')->store('public/donation-bill');
        $filename = $request->file('file')->hashName();
        $file = url('/') . '/storage/donation-bill/' . $filename;
        $donation_bills = DonationBill::create([
            'donation_id' => request('donation_id'),
            'family_member_id' => auth('api')->user()->family_member->id,
            'nominal' => request('nominal'),
            'file' => $file,
            'status' => 'lunas'
        ]);

        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'warga bayar iuran'
        ]);

        DashboardNotification::create([
            'category' => 'Bayar Iuran',
            'description' => $donation_bills->status . '(' . auth('api')->user()->family_member->family_member_name . ')'
        ]);

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menyimpan bukti iuran';
        $data = ['iuran_bills' => $donation_bills];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
}
