<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\Polling;
use App\Models\PollingOption;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollingController extends Controller
{
    function notify($title, $body, $image = null)
    {
        return new PushNotification($title, $body, $image);
    }
    public function index()
    {
        $pollings['pollings'] = Polling::orderBy('id', 'asc')->get();
        return view('dashboard.polling.index', $pollings);
    }
    public function store(Request $request)
    {
        $polling = Polling::create([
            'title' => request('title'),
            'description' => request('description'),
            'status' => 'pending'
        ]);
        $polling_option = request('option_name');
        for ($i = 0; $i < count($polling_option); $i++) {
            PollingOption::create([
                'polling_id' => $polling->id,
                'option_name' => $polling_option[$i]
            ]);
        }
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Tambah polling'
        ]);

        return redirect()->back()->with('OK', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $polling = Polling::with('polling_option')->findOrFail($id);
        return $polling;
    }
    public function update($id)
    {
        $polling = Polling::with('polling_option')->findOrFail($id);
        $polling_option = PollingOption::where('polling_id', $polling->id);
        $polling_option->delete();

        $polling->update([
            'title' => request('title'),
            'description' => request('description')
        ]);
        $polling_option = request('option_name');
        for ($i = 0; $i < count($polling_option); $i++) {
            PollingOption::create([
                'polling_id' => $polling->id,
                'option_name' => $polling_option[$i]
            ]);
        }
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Ubah polling'
        ]);

        return redirect()->back()->with('OK', 'Berhasil mengubah data');
    }
    public function destroy(Polling $polling)
    {
        $polling->delete();
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Hapus polling'
        ]);

        return redirect()->back()->with('OK', 'Berhasil menghapus data');
    }
    public function startPolling(Polling $polling)
    {
        $polling->update(['status' => 'start']);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Mulai polling'
        ]);
        $notification = 'Tidak ada fcm_token pada user';
        $users = User::whereHas('family_member', function ($q) {
            $q->whereHas('family_card', function ($q1) {
                $q1->whereHas('house');
            });
        })->pluck('fcm_token');

        $title_notif = 'Polling dimulai, ' . $polling->title;
        $body_notif = $polling->description;
        foreach ($users as $user) {
            if ($user != null) {
                $notification = $this->notify('e-polling: ' . $title_notif, $body_notif)
                    ->data([
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'title' => $title_notif,
                        'body' => $body_notif,
                        'type' => 'Polling'
                    ])->to($user)->send();
            }
        }

        return redirect()->back()->with('OK', 'Berhasil memulai polling');
    }
    public function finishPolling(Polling $polling)
    {
        $polling->update(['status' => 'finish']);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Akhiri polling'
        ]);
        $notification = 'Tidak ada fcm_token pada user';
        $users = User::whereHas('family_member', function ($q) {
            $q->whereHas('family_card', function ($q1) {
                $q1->whereHas('house');
            });
        })->pluck('fcm_token');

        $title_notif = 'Polling Berakhir, ' . $polling->title;
        $body_notif = $polling->description;
        foreach ($users as $user) {
            if ($user != null) {
                $notification = $this->notify('e-polling: ' . $title_notif, $body_notif)
                    ->data([
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'title' => $title_notif,
                        'body' => $body_notif,
                        'type' => 'Polling'
                    ])->to($user)->send();
            }
        }

        return redirect()->back()->with('OK', 'Berhasil mengakhiri polling');
    }
}
