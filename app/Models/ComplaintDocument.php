<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintDocument extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
