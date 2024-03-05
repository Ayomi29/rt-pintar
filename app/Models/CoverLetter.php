<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverLetter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function family_member()
    {
        return $this->belongsTo(FamilyMember::class);
    }
}
