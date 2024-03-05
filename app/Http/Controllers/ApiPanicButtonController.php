<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\DashboardNotification;
use App\Models\LocationPanicButton;
use App\Models\PanicButton;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;
use LaravelFCM\Facades\FCM;

class ApiPanicButtonController extends Controller
{
    function notify($title, $body, $image = null)
    {
        return new PushNotification($title, $body, $image);
    }
    public function index()
    {
        $panic_button = LocationPanicButton::where('status', 'menunggu')->orderBy('created_at', 'desc')->get();
        if (count($panic_button) < 1) {
            $panic_button = null;
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data panic button';
        $data = ['panic_button' => $panic_button];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function store(Request $request)
    {
        $panic_button = LocationPanicButton::where('user_id', auth('api')->user()->id)->first();
        if ($panic_button != null) {
            $panic_button->delete();
        }

        $setting_panic_button = PanicButton::orderBy('id', 'asc')->first();
        if (auth('api')->user()->family_member == true) {
            $data = LocationPanicButton::create([
                'user_id' => auth('api')->user()->id,
                'username' => auth('api')->user()->family_member->family_member_name . "(Warga)",
                'phone_number' => auth('api')->user()->phone_number,
                'house_number' => auth('api')->user()->family_member->family_card->house->house_number,
                'latitude' => auth('api')->user()->family_member->family_card->house->latitude,
                'longitude' => auth('api')->user()->family_member->family_card->house->longitude,
                'status' => 'menunggu'
            ]);
        }
        
        $notification = 'Tidak ada fcm_token pada user';
        $users = User::whereHas('pengurus')->pluck('fcm_token');
        $title = "Notifikasi Darurat";
        $address = auth('api')->user()->family_member->address;
        $house = auth('api')->user()->family_member->family_card->house->house_number;
        $description = "Keadaan darurat pada nomor rumah" . $house . "atau pada alamat:" . $address;
        foreach ($users as $user) {
            if ($user != null) {
                $notification = $this->notify('Bahaya: ' . $title, $description)
                    ->data([
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'PANIC_BUTTON',
                        'title' => $title,
                        'body' => $description
                    ])->to($user)->send();
            }
        }
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Menekan panik button'
        ]);

        DashboardNotification::create([
            'category' => 'Panik button',
            'description' => $data->username . ' Menekan panik button'
        ]);

        $status = 'OK';
        $status_code = 'DBC-200';
        $message = 'Berhasil mengirim data';
        return response()->json(compact('status', 'status_code', 'message'));
    }

    /**
     * Display the specified resource.
     */
    public function close($id)
    {
        $panic_button1 = LocationPanicButton::findOrFail($id);
        $panic_button1->update([
            'status' => request('status'),
            'description' => request('description')
        ]);
        $panic_button = LocationPanicButton::findOrFail($id);

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengupdate data';
        return response()->json(compact('status', 'status_code', 'message', 'panic_button'));
    }
}
