<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationBill extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function family_member()
    {
        return $this->belongsTo(FamilyMember::class);
    }
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
