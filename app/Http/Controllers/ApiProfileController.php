<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Http\Request;

class ApiProfileController extends Controller
{

    public function getProfile()
    {
        if (auth('api')->user()->family_member == true) {
            $user = User::with('family_member.family_card.house')->where('id', auth('api')->user()->id)->first();
        }

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data profile';
        $data = ['user' => $user];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function updateProfile(Request $request)
    {
        if (auth('api')->user()->family_member == true) {
            $familyMember = FamilyMember::where('user_id', auth('api')->user()->id)->first();
            $avatar = $familyMember->avatar;
            if ($request->hasFile('avatar')) {
                $save = $request->file('avatar')->store('public/avatar');
                $filename = $request->file('avatar')->hashName();
                $avatar = url('/') . '/storage/avatar/' . $filename;
            }
            $familyMember->update([
                'avatar' => $avatar
            ]);
            ActivityHistory::create([
                'user_id' => auth('api')->user()->id,
                'description' => 'Ubah profil'
            ]);
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah data profile';
        $data = ['user' => $familyMember];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
    public function updatePhoneNumber(Request $request)
    {
        $user = User::where('phone_number', request('phone_number'));
        $user->update([
            'phone_number' => request('phone_number')
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah data profile';
        $data = ['user' => $user];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
    public function updatePassword(Request $request)
    {
        $user = User::where('id', auth('api')->user()->id)->first();
        $user->update([
            'password' => bcrypt(request('password')) 
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah password akun';
        return response()->json(compact('status', 'status_code', 'message'), 200);
    }
}
