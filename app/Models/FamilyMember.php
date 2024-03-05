<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function family_card()
    {
        return $this->belongsTo(FamilyCard::class);
    }
    public function cover_letter()
    {
        return $this->hasMany(CoverLetter::class);
    }
}
