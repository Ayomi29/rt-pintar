<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function donation_bill()
    {
        return $this->hasMany(DonationBill::class);
    }
}
