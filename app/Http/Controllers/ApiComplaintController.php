<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\Complaint;
use App\Models\ComplaintDocument;
use App\Models\DashboardNotification;
use Illuminate\Http\Request;

class ApiComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $complaint = Complaint::with('complaint_document')
            ->orWhere('status', 'diposting')->orWhere('status', 'selesai')
            ->orderBy('created_at', 'desc')->get();

        if (count($complaint) < 1) {
            $complaint = null;
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data komplain warga';
        $data = ['complaint' => $complaint];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function history()
    {
        $complaint = Complaint::with('complaint_document')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data komplain warga';
        $data = ['complaint' => $complaint];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function store(Request $request)
    {
        if (request('title') == null || request('title') == '' || request('description') == null || request('description') == '') {
            $status = 'error';
            $status_code = 400;
            $message = 'Semua kolom wajib diisi';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
        $complaint = Complaint::create([
            'user_id' => auth('api')->user()->id,
            'title' => request('title'),
            'description' => request('description'),
            'status' => 'diselidiki'
        ]);

        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Tambah aduan warga'
        ]);

        DashboardNotification::create([
            'category' => 'Aduan warga',
            'description' => $complaint->title . '|' . $complaint->description . '(' . auth('api')->user()->family_member->family_member_name . ')'
        ]);

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengirim data';
        $data = ['complaint' => $complaint];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function storeDocument(Request $request)
    {
        $complaint = Complaint::where('id', request('complaint_id'))->first();

        if ($request->hasFile('document')) {
            $save = $request->file('document')->store('public/document');
            $filename = $request->file('document')->hashName();
            $document = url('/') . '/storage/document/' . $filename;
        }
        $complaint_document = ComplaintDocument::create([
            'complaint_id'  => $complaint->id,
            'document' => $document
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengirim data';
        $data = ['complaint' => $complaint];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data komplain';
        $data = ['complaint' => $complaint];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function indexAdmin()
    {
        if (auth('api')->user()->pengurus == true || auth('api')->user()->admin == true) {
            $complaint = Complaint::with('complaint_document')->where('status', 'diselidiki')->get();
            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil mendapatkan data';
            return response()->json(compact('status', 'status_code', 'message', 'complaint'), 200);
        } else {
            $complaint = null;
            $status = 'error';
            $status_code = 400;
            $message = 'Anda bukan pengurus';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
    }

    public function updateStatusComplaint($id)
    {
        if (auth('api')->user()->pengurus == true || auth('api')->user()->admin == true) {
            $complaint = Complaint::where('id', $id)->update([
                'status' => 'diposting'
            ]);

            ActivityHistory::create([
                'user_id' => auth('api')->user()->id,
                'description' => 'Pengurus | Mengubah status aduan warga'
            ]);

            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil mengubah status';
            return response()->json(compact('status', 'status_code', 'message'), 200);
        } else {
            $status = 'error';
            $status_code = 400;
            $message = 'Anda bukan pengurus';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
    }
}
