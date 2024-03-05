<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\Notice;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;

class ApiNoticeController extends Controller
{
    function notify($title, $body, $image = null)
    {
        return new PushNotification($title, $body, $image);
    }
    public function index()
    {
        if (auth('api')->user()->family_member == true) {
            $notice = Notice::where('status', 'aktif')->orderBy('created_at', 'desc')->get();
        }
        if (count($notice) < 1) {
            $notice = null;
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data pengumuman';
        $data = ['notice' => $notice];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (request('title') == null || request('title') == '' || request('description') == null || request('description') == '') {
            $status = 'error';
            $status_code = 400;
            $message = 'Semua kolom wajib diisi';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
        $notice = Notice::create([
            'title' => request('title'),
            'description' => request('description'),
            'status' => 'aktif'
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Membuat pengumuman'
        ]);
        if(request('status') == 'aktif') {
            $notification = 'Tidak ada fcm_token pada user';
            $users = User::whereHas('family_member', function ($q) {
                $q->whereHas('family_card', function ($q1) {
                    $q1->whereHas('house');
                });
            })->pluck('fcm_token');
    
            $title_notif = 'Pengumuman, ' . request('title');
            $body_notif = request('description');
            foreach ($users as $user) {
                if ($user != null) {
                    $notification = $this->notify($title_notif, $body_notif)
                        ->data([
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'title' => $title_notif,
                            'body' => $body_notif,
                        ])->to($user)->send();
                }
            }
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil membuat pengumuman';
        $data = ['notice' => $notice];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function show($id)
    {
        $notice = Notice::findOrFail($id);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data pengumuman';
        $data = ['notice' => $notice];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
    
}
