<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyCard extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function house()
    {
        return $this->belongsTo(FamilyCard::class);
    }

    public function family_member()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function available_family_member()
    {
        return $this->hasMany(FamilyMember::class)->whereDoesntHave('user');
    }
}
