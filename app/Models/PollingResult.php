<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingResult extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function polling_option()
    {
        return $this->belongsTo(PollingOption::class);
    }
    public function family_member()
    {
        return $this->belongsTo(FamilyMember::class);
    }
}
