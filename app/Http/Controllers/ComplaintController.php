<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\Complaint;
use App\Models\ComplaintDocument;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    function notify($title, $body, $image = null)
    {
        return new PushNotification($title, $body, $image);
    }
    public function index()
    {
        $complaints["complaints"] = Complaint::orderBy('id', 'asc')->get();
        return view('dashboard.complaint.index', $complaints);
    }

    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint_image = ComplaintDocument::where('complaint_id', $id)->where(function($q) {
            $q->where('document', 'LIKE', '%.jpg%')->orWhere('document', 'LIKE', '%.jpeg%')->orWhere('document', 'LIKE', '%.png%');
        })->get();
        $complaint_video = ComplaintDocument::where('complaint_id', $id)->where('document', 'LIKE', '%.mp4%')->get();
        if ($complaint->complaint_document == false) {
            return redirect()->back()->with('ERR', 'Aduan ini tidak memiliki dokumen');
        }
        return view('dashboard.complaint.show', [
            'complaint' => $complaint,
            'complaint_image' => $complaint_image,
            'complaint_video' => $complaint_video
        ]);
    }
    public function edit($complaint)
    {
        $complaint = Complaint::findOrFail($complaint);
        return $complaint;
    }
    public function update(Request $request, Complaint $complaint)
    {
        $complaint->update([
            'status' => request('status'),
        ]);

        if (request('status') == 'diposting') {
            $users = User::whereHas('family_member', function ($q) {
                $q->whereHas('family_card', function ($q1) {
                    $q1->whereHas('house');
                });
            })->pluck('fcm_token');

            $title_notif = 'Aduan, ' . $complaint->title;
            $body_notif = $complaint->description;
            foreach ($users as $user) {
                if ($user != null) {
                    $notification = $this->notify($title_notif, $body_notif)
                        ->data([
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'title' => $title_notif,
                            'body' => $body_notif
                        ])->to($user)->send();
                }
            }
        }
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Mengubah data komplain'
        ]);
        return redirect()->back()->with("OK", "Berhasil mengubah data");
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Menghapus data komplain'
        ]);
        return redirect()->back()->with('OK', 'Berhasil menghapus data');
    }
}
