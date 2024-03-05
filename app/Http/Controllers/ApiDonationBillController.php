<?php

namespace App\Http\Controllers;

use App\Models\DonationBill;
use Illuminate\Http\Request;

class ApiDonationBillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donation_bills = DonationBill::where('status', 'lunas')->orderBy('created_at', 'desc')->get();
        if (count($donation_bills) < 1) {
            $donation_bills = null;
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data yang sudah bayar iuran';
        $data = ['iuran_bills' => $donation_bills];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function show($id)
    {
        $donation_bills = DonationBill::where('id', $id)->get();
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan detail bukti bayar iuran';
        $data = ['iuran_bills' => $donation_bills];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
}
