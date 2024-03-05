<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polling extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function polling_option()
    {
        return $this->hasMany(PollingOption::class);
    }
}
