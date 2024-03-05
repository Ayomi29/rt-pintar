<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingOption extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function polling()
    {
        return $this->belongsTo(Polling::class);
    }
    public function polling_result()
    {
        return $this->hasMany(PollingResult::class);
    }
}
