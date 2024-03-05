<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\Notice;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    function notify($title, $body, $image = null)
    {
        return new PushNotification($title, $body, $image);
    }
    public function index()
    {
        $notices['notices'] = Notice::orderBy('id', 'asc')->get();
        return view('dashboard.notice.index', $notices);
    }



    public function store(Request $request)
    {
        Notice::create([
            'title' => request('title'),
            'description' => request('description'),
            'status' => 'aktif'
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
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
        return redirect()->back()->with('OK', "Berhasil menambahkan data");
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        return $notice;
    }

    public function update(Request $request, Notice $notice)
    {
        $notice->update([
            'title' => request('title'),
            'description' => request('description'),
            'status' => request('status')
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Mengubah pengumuman'
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
        return redirect()->back()->with('OK', "Berhasil mengubah data");
    }

    public function destroy(Notice $notice)
    {

        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Menghapus pengumuman'
        ]);
        $notice->delete();

        return redirect()->back()->with('OK', "Berhasil menghapus data");
    }
}
