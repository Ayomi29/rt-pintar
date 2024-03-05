<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\Polling;
use App\Models\PollingOption;
use App\Models\PollingResult;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;

class ApiPollingController extends Controller
{
    function notify($title, $body, $image = null)
    {
        return new PushNotification($title, $body, $image);
    }
    public function index()
    {
        $polling = Polling::with('polling_option')->where('status', 'start')->orWhere('status', 'finish')->orderBy('created_at', 'desc')->get();
        if (count($polling) < 1) {
            $polling = null;
        }

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data polling';
        $data = ['polling' => $polling];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (request('polling_option_id') == null || request('polling_option_id') == '') {
            $status = 'error';
            $status_code = 400;
            $message = 'Semua kolom wajib diisi';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
        $polling_option = PollingOption::findOrFail(request('polling_option_id'));
        $check_polling = PollingOption::where('polling_id', $polling_option->polling_id)
            ->whereHas('polling_result', function ($q) {
                $q->where('family_member_id', auth('api')->user()->family_member->id);
            })->first();
        if ($check_polling != null) {
            $check_polling->update([
                'vote' => (int) $check_polling->vote - 1
            ]);
            $revoting = PollingResult::where([['polling_option_id', $check_polling->id], ['family_member_id', auth('api')->user()->family_member->id]])->first();
            $revoting->update([
                'polling_option_id' => request('polling_option_id')
            ]);
            $polling_option = PollingOption::where('id', $revoting->polling_option_id)->first();
            $polling_option->update([
                'vote' => (int) $polling_option->vote + 1
            ]);
            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil mengirim ulang hasil voting';
            $data = ['polling' => $revoting];
            return response()->json(compact('status', 'status_code', 'message', 'data'));
        } else {
            $voting = PollingResult::create([
                'family_member_id' => auth('api')->user()->family_member->id,
                'polling_option_id' => request('polling_option_id')
            ]);

            $polling_option = PollingOption::where('id', $voting->polling_option_id)->first();
            $polling_option->update([
                'vote' => (int)$polling_option->vote + 1
            ]);

            ActivityHistory::create([
                'user_id' => auth('api')->user()->id,
                'description' => 'Vote polling'
            ]);

            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil mengirim hasil voting';
            $data = ['polling' => $voting];
            return response()->json(compact('status', 'status_code', 'message', 'data'));
        }
    }
    public function show($id)
    {
        $polling = Polling::with('polling_option')->where('id', $id)->first();
        $check_polling = PollingOption::where('polling_id', $id)
            ->whereHas('polling_result', function ($q) {
                $q->where('family_member_id', auth('api')->user()->family_member->id);
            })->first();
        if ($check_polling != null) {
            $status = 'success';
            $status_code = 200;
            $message = 'Sudah voting';
        } else {
            $status = 'error';
            $status_code = 404;
            $message = 'Belum voting';
        }
        return response()->json(compact('status', 'status_code', 'message', 'polling', 'check_polling'));
    }

    public function createPolling()
    {
        if (auth('api')->user()->admin == true || auth('api')->user()->pengurus == true) {
            $polling = Polling::create([
                'title' => request('title'),
                'description' => request('description'),
                'status' => 'pending',
            ]);

            $polling_option = request('option_name');
            for ($i = 0; $i < count($polling_option); $i++) {
                PollingOption::create([
                    'polling_id' => $polling->id,
                    'option_name' => $polling_option[$i]
                ]);
            }

            ActivityHistory::create([
                'user_id' => auth('api')->user()->id,
                'description' => 'Tambah polling'
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
            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil membuat polling';
            $data = ['polling' => $polling];
            return response()->json(compact('status', 'status_code', 'message', 'data'));
        }
    }
    
}
