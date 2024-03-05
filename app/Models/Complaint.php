<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function complaint_document()
    {
        return $this->hasMany(ComplaintDocument::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
