<?php

namespace App\Http\Controllers;


use App\Models\ActivityHistory;
use App\Models\Donation;
use App\Models\DonationBill;
use App\Models\FamilyMember;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    function notify($title, $body, $image = null)
    {
        return new PushNotification($title, $body, $image);
    }
    public function index()
    {
        $donations["donations"] = Donation::orderBy('id', 'asc')->get();
        return view('dashboard.donation.index', $donations);
    }
    public function show($id)
    {
        $donations = DonationBill::where('donation_id', $id)->get();
        $sum = DonationBill::where('donation_id', $id)->pluck('nominal')->map(function ($value) {
            return (int) preg_replace('/[^\d]/', '', $value);
        })->sum();
        $sum_donation = number_format($sum, 0);
        return view('dashboard.donation.show', [
            'donations' => $donations,
            'sum_donation' => $sum_donation
        ]);
    }
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $request->file('image')->store('public/picture');
            $filename = $request->file('image')->hashName();
            
            $img = '/storage/picture/' . $filename;
        }

        Donation::create([
            'title' => request('title'),
            'description' => request('description'),
            'nominal' => request('nominal'),
            'image' => $img
        ]);
        ActivityHistory::create([
            'user_id' => auth()->user()->id,
            'description' => 'Membuat iuran'
        ]);
        $notification = 'Tidak ada fcm_token pada user';
        $users = User::whereHas('family_member', function ($q) {
            $q->whereHas('family_card', function ($q1) {
                $q1->whereHas('house');
            });
        })->pluck('fcm_token');

        $title_notif = 'Iuran, ' . request('title');
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
        return redirect()->back()->with('OK', 'Berhasil menambahkan data');
    }

    
    public function edit($id)
    {
        $donation = Donation::findOrFail($id);
        return $donation;
    }

    public function update(Request $request, $id)
    {
        $donation = Donation::find($id);
        // dd($donation);
        if ($request->image) {
            $donation->image = $request->file('image');
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $request->file('image')->store('public/picture');
            $filename = $request->file('image')->hashName();
            // $img = url('/') . '/storage/picture/' . $filename;
            $img = '/storage/picture/' . $filename;
        }
        $donation->update([
            'title' => request('title'),
            'description' => request('description'),
            'nominal' => request('nominal'),
            'image' => $img
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Mengubah data iuran'
        ]);
        return redirect()->back()->with('OK', 'Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Menghapus data iuran'
        ]);
        Donation::destroy($id);


        return redirect()->back()->with('OK', 'Berhasil menghapus data');
    }
}
