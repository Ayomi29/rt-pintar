<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\FamilyCard;
use App\Models\FamilyMember;
use App\Models\House;
use App\Models\LocationPanicButton;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        
        $data['year'] = Carbon::now(8)->format('Y');
        if (auth()->user()->admin == true) {
            $data['admin'] = $this->getAdmin();
            $data['user'] = $this->getUser();
            $data['family_card'] = $this->getFamilyCard();
            $data['people'] = $this->getPeople();
            $data['man'] = $this->getMan();
            $data['woman'] = $this->getWoman();
            $data['house'] = $this->getHouse();
            $data['empty_house'] = $this->getEmptyHouse();
            $data['activity_history'] = $this->getActivityHistory();
        } else {
            return redirect()->back()->with("ERR", "Tidak memiliki hak akses");
        }
        return view('dashboard.home.index', $data);
    }
    public function getFamilyCard()
    {
        $family_card = FamilyCard::whereHas('house')->get();

        $count_family_card = count($family_card);

        return $count_family_card;
    }
    public function getActivityHistory()
    {
        $activity = ActivityHistory::orderBy('id', 'desc')->get();

        return $activity;
    }
    public function getHouse()
    {
        $house = House::whereHas('family_card', function ($q) {
            $q->where('status', 'aktif');
        })->get();

        $count_house = count($house);

        return $count_house;
    }

    public function getEmptyHouse()
    {
        $empty_house = House::whereDoesntHave('family_card', function ($q) {
            $q->where('status', 'aktif');
        })->get();

        $count_empty_house = count($empty_house);

        return $count_empty_house;
    }

    public function getMan()
    {
        $man = FamilyMember::where('gender', 'laki-laki')->whereHas('family_card', fn ($q) => $q->where('status', 'aktif'))->get();
        $count_man = count($man);
        return $count_man;
    }

    public function getWoman()
    {
        $woman = FamilyMember::where('gender', 'perempuan')->whereHas('family_card', fn ($q) => $q->where('status', 'aktif'))->get();
        $count_woman = count($woman);
        return $count_woman;
    }
    public function getAdmin()
    {
        $admin = Role::where('role_name', 'admin')->get();
        $count_admin = count($admin);
        return $count_admin;
    }

    public function getUser()
    {
        $family_member = FamilyMember::where('user_id', '!=', 'null')->whereHas('family_card', function ($q) {
            $q->whereHas('house');
        })->get();

        $count_user = count($family_member);

        return $count_user;
    }

    public function getPeople()
    {
        $family_member = FamilyMember::whereHas('family_card', function ($q) {
            $q->whereHas('house');
        })->get();

        $count_people = count($family_member);

        return $count_people;
    }

}
