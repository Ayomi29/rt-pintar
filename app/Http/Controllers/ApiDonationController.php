<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\DashboardNotification;
use App\Models\Donation;
use App\Models\DonationBill;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiDonationController extends Controller
{
    
    public function Test()
    {
        $id_fm = DonationBill::where('donation_id', 1)->pluck('family_member_id');
        $id_fc = FamilyMember::with('family_card')->whereIn('id', $id_fm)->pluck('family_card_id');
        $card = auth('api')->user()->family_member->family_card->id;
        if($id_fc->contains($card)) {
            $status = 'error';
            $status_code = 400;
            $message = 'Keluarga anda sudah ada yang bayar';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        } else {
            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil mendapatkan data iuran';
            return response()->json(compact('status', 'status_code', 'message'), 200);
        }
    }
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
    
    public function show($id)
    {
        $id_fm = DonationBill::where('donation_id', $id)->pluck('family_member_id');
        $id_fc = FamilyMember::with('family_card')->whereIn('id', $id_fm)->pluck('family_card_id');
        $card = auth('api')->user()->family_member->family_card->id;
        if ($id_fc->contains($card)) {
            $status = 'error';
            $status_code = 400;
            $message = 'Keluarga anda sudah ada yang bayar';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        } else {
            $donation = Donation::findOrFail($id);
            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil mendapatkan detail data iuran';
            $data = ['iuran' => $donation];
            return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
        }
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
