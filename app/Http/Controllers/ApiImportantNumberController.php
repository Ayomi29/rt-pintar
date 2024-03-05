<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\ImportantNumber;
use Illuminate\Http\Request;

class ApiImportantNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $important_number = ImportantNumber::all();
        if (count($important_number) < 1) {
            $important_number = null;
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data nomor penting';
        $data = ['important_number' => $important_number];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }


    
}
